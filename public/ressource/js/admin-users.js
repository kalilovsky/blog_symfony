Array.from(document.getElementsByClassName("editContent")).forEach(e => {
    e.addEventListener("click", () => {
        const url = e.dataset.url;
        let formData = new FormData();
        const dataToSend = {
            method: "POST",
            body: formData
        }
        fetch(url, dataToSend)
            .then(data => data.json())
            .then(data => {
                document.getElementById("pseudo").value = data.pseudo;
                document.getElementById("firstname").value = data.firstname;
                document.getElementById("lastname").value = data.lastname;
                document.getElementById("email").value = data.email;
                document.getElementById("idUsers").value = data.id;
                document.getElementById("userType").value = data.usertype;
                document.getElementById("photo").src = document.getElementById("photo").src.replace(document.getElementById("photo").dataset.src, data.photouser);
                document.getElementById("photo").dataset.src = data.photouser;
                document.getElementById("file").value = "";
                document.getElementById("editPopup").classList.toggle("visible");
            });

    })
})
document.getElementById("popUpCloseBtn").addEventListener("click", () => {
    document.getElementById("editPopup").classList.toggle("visible");
})
document.getElementById("editPopupForm").addEventListener("submit", e => {
    e.preventDefault();
    switch (e.submitter.id) {
        case "updateButton": {
            let formData = new FormData(e.target);
            const url = e.submitter.dataset.url + document.getElementById("idUsers").value;
            const options = {
                method: "post",
                body: formData
            }
            fetch(url, options)
                .then(data => data.json())
                .then(data => {
                    let child = document.getElementById(data.id).children;
                    let img = child[1].querySelector("img");
                    img.src = img.src.replace(img.dataset.src, data.photouser);
                    img.dataset.src = data.photouser;
                    child[2].innerHTML = data.pseudo;
                    child[3].innerHTML = data.firstname;
                    child[4].innerHTML = data.lastname;
                    child[5].innerHTML = data.usertype;
                    document.getElementById("editPopup").classList.toggle("visible");
                })
            break;
        }
        case "deleteButton": {
            let formData = new FormData(e.target);
            const url = e.submitter.dataset.url + document.getElementById("idUsers").value;
            const options = {
                method: "post",
                body: formData
            }
            fetch(url, options)
                .then(data => data.json())
                .then(data => {
                    document.getElementById(data).remove();
                    document.getElementById("registredUser").innerHTML = ~~document.getElementById("registredUser").innerHTML -1;
                    document.getElementById("editPopup").classList.toggle("visible");
                });
            break;
        }
    }
})
window.onclick = function(e) {
    if (e.target == document.getElementById("editPopup")) {
        document.getElementById("editPopup").classList.remove("visible");
    }
}