# Função para habilitar o WSL e o Hyper-V
function Enable-WSL {
    dism.exe /online /enable-feature /featurename:Microsoft-Windows-Subsystem-Linux /all /norestart
    dism.exe /online /enable-feature /featurename:VirtualMachinePlatform /all /norestart
    Write-Host "WSL e Hyper-V habilitados. Reinicie o computador para aplicar as alterações."
    pause
    exit
}

# Função para instalar o WSL
function Install-WSL {
    wsl --install
}

# Função para instalar o Docker
function Install-Docker {
    # Baixa e instala o Docker Desktop
    Invoke-WebRequest -Uri https://desktop.docker.com/win/stable/Docker%20Desktop%20Installer.exe -OutFile DockerInstaller.exe
    Start-Process -FilePath .\DockerInstaller.exe -Wait
    Remove-Item -Path .\DockerInstaller.exe

    # Espera o Docker ser iniciado
    Start-Sleep -Seconds 20

    # Verifica se o Docker foi instalado corretamente
    if (Get-Command docker -ErrorAction SilentlyContinue) {
        Write-Host "Docker instalado com sucesso."
    } else {
        Write-Host "Falha ao instalar o Docker."
        Exit
    }
}

# Função para instalar o Docker Compose
function Install-DockerCompose {
    Invoke-WebRequest "https://github.com/docker/compose/releases/download/v2.5.0/docker-compose-Windows-x86_64.exe" -OutFile "$Env:ProgramFiles\Docker\Docker\resources\bin\docker-compose.exe"
    [Environment]::SetEnvironmentVariable("Path", $Env:Path + ";$Env:ProgramFiles\Docker\Docker\resources\bin", [EnvironmentVariableTarget]::Machine)
    
    # Verifica se o Docker Compose foi instalado corretamente
    if (Get-Command docker-compose -ErrorAction SilentlyContinue) {
        Write-Host "Docker Compose instalado com sucesso."
    } else {
        Write-Host "Falha ao instalar o Docker Compose."
        Exit
    }
}

# Habilita o WSL se necessário
if (-not (Get-Command wsl -ErrorAction SilentlyContinue)) {
    Write-Host "Habilitando WSL..."
    Enable-WSL
}

# Instala o WSL se necessário
if (-not (Get-Command wsl -ErrorAction SilentlyContinue)) {
    Write-Host "Instalando WSL..."
    Install-WSL
}

# Verifica se o Docker está instalado, caso contrário, instala
if (-not (Get-Command docker -ErrorAction SilentlyContinue)) {
    Write-Host "Instalando Docker..."
    Install-Docker
}

# Verifica se o Docker Compose está instalado, caso contrário, instala
if (-not (Get-Command docker-compose -ErrorAction SilentlyContinue)) {
    Write-Host "Instalando Docker Compose..."
    Install-DockerCompose
}

Write-Host "Reinicie o computador para concluir a instalação do Docker. Depois de reiniciar, certifique-se de que o Docker Desktop está em execução antes de continuar."