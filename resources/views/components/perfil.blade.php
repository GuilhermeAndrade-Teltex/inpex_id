@vite(['resources/js/components/perfil.module.js'])

<div id="userbox" class="userbox">
    <a href="#" data-bs-toggle="dropdown">
        <figure class="profile-picture">
            <img src="{{ $image }}" alt="{{ $name }}" class="rounded-circle" />
        </figure>
        <div class="profile-info">
            <span class="name">{{ $name }}</span>
            <span class="role">{{ $department }}</span>
        </div>
        <i class="fa custom-caret"></i>
    </a>
    <div class="dropdown-menu">
        <ul class="list-unstyled mb-2">
            <li class="divider"></li>
            <li>
                {{-- <a role="menuitem" tabindex="-1" href="{{ route('user.profile') }}"><i
                        class="bx bx-user-circle"></i>
                    Meu Perfil</a> --}}
                <a role="menuitem" tabindex="-1" href=""><i class="bx bx-user-circle"></i>
                    Meu Perfil</a>
            </li>
            <li>
                <x-utils.btn_open_modal animation="zoom" icon="bx bx-power-off" />
            </li>
        </ul>
    </div>

    <x-utils.modal headerTitle="Confirmar" maxWidth="xs"
        footerBtn1='{{ json_encode(array("id" => "btn_logout", "label" => "Cadastrar", "color" => "primary")) }}'
        footerBtn2='{{ json_encode(array("id" => "btn_cancel", "label" => "Cancelar", "color" => "default")) }}'>
        <p>Deseja efetuar o logout ?</p>
    </x-utils.modal>
</div>