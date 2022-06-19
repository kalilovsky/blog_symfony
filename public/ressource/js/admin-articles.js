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
                console.log(data);
                document.getElementById("pseudo").innerHTML = data.user.pseudo;
                document.getElementById("email").innerHTML = data.user.email;
                document.getElementById("creationDate").innerHTML = data.creationdate;
                document.getElementById("title").value = data.title;
                document.getElementById("smallDesc").value = data.smalldesc;
                document.getElementById("photo").src = document.getElementById("photo").src.replace(document.getElementById("photo").dataset.src, '');
                document.getElementById("photo").src += data.photoarticle;
                document.getElementById("photo").dataset.src = data.photoarticle;
                document.getElementById("content").value = data.content;
                document.getElementById("idArticle").value = data.id;
                document.getElementById("idCategory").value = data.category.id;
                document.getElementById("editPopup").classList.toggle("visible");
            });


    })
})
document.getElementById("popUpCloseBtn").addEventListener("click", () => {
    document.getElementById("editPopup").classList.toggle("visible");
})
window.onclick = function (e) {
    if (e.target == document.getElementById("editPopup")) {
        document.getElementById("editPopup").classList.remove("visible");
    }
}

document.getElementById("editPopupForm").addEventListener("submit", e => {
    e.preventDefault();
    switch (e.submitter.id) {
        case "updateButton": {
            let formData = new FormData(e.target);
            const url = e.submitter.dataset.url + document.getElementById("idArticle").value;
            const options = {
                method: "post",
                body: formData
            }
            fetch(url, options)
                .then(data => data.json())
                .then(data => {
                    let child = document.getElementById(data["id"]).children;
                    child[1].innerHTML = data["title"];
                    child[2].innerHTML = data.category["catname"];
                    child[3].innerHTML = data.user["pseudo"];
                    child[4].innerHTML = data["creationdate"];
                    child[5].innerHTML = data["updatedate"];
                    document.getElementById("editPopup").classList.remove("visible");
                });
            break;
        }
        case "deleteButton": {
            let formData = new FormData(e.target);
            const url = e.submitter.dataset.url + document.getElementById("idArticle").value;
            const options = {
                method: "post",
                body: formData
            }
            fetch(url, options)
                .then(data => data.json())
                .then(data => {
                    document.getElementById(data).remove();
                    document.getElementById("editPopup").classList.remove("visible");
                });
            break;
            break
        }
    }


})