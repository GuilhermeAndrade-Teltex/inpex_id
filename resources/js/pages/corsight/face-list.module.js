setInterval(function () {
    fetch("/corsight/faces-data")
        .then((response) => response.json())
        .then((faces) => {
            console.log("eu voltei");

            const facesList = document.getElementById("faces-list");
            facesList.innerHTML = "";
            faces.forEach((face) => {
                const listItem = document.createElement("li");
                listItem.className = "listItem";
                listItem.style =
                    "opacity: 1; transform: none; margin: 10px;";
                const div = document.createElement("div");
                const img = document.createElement("img");
                img.src = `/storage/${face.face_crop_img}`;
                img.alt = "Face Image";
                img.style =
                    "width: 160px; height: 240px; object-fit: cover;";
                const p = document.createElement("p");
                p.textContent = face.poi_display_name;
                div.appendChild(img);
                div.appendChild(p);
                listItem.appendChild(div);
                facesList.appendChild(listItem);
            });
        });
}, 10000);