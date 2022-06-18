Array.from(document.getElementsByClassName("editContent")).forEach(e => {
    e.addEventListener("click", () => {
        const url = e.dataset.url;
        fetch(url)
            .then(data => data.json())
            .then(data => {
                document.getElementById("idComment").value = data.id;
                document.getElementById("pseudo").innerHTML = data.user.pseudo;
                document.getElementById("email").innerHTML = data.user.email;
                document.getElementById("title").innerHTML = data.article.title;
                document.getElementById("status").value = data.status ? '1' : 0;
                document.getElementById("status").dataset.status = data.status ? '1' : 0; 
                document.getElementById("messageContent").value = data.contentcomment;
                document.getElementById("editPopup").classList.toggle("visible");
            });

    })
})
document.getElementById("popUpCloseBtn").addEventListener("click", event => {
    document.getElementById("editPopup").classList.toggle("visible");
})
window.onclick = function (e) {
    if (e.target == document.getElementById("editPopup")) {
        document.getElementById("editPopup").classList.remove("visible");
    }
}


//update and delete Comment
document.getElementById("editPopupForm").addEventListener("submit", e => {
    switch (e.submitter.id) {
        //update status
        case "updateButton":
            e.preventDefault();
            let formData = new FormData(e.currentTarget);
            const url = e.submitter.dataset.url + document.getElementById("idComment").value;
            const options = {
                method: "post",
                body: JSON.stringify(Object.fromEntries(formData))
            }
            fetch(url, options)
                .then(data => data.json())
                .then(data => {
                    let statusText = ["0", "1"];
                    let selectedStatus = statusText[document.getElementById("status").value];
                    let originalStatus = document.getElementById("status").dataset.status;
                    if (originalStatus != selectedStatus) {
                        if (selectedStatus == statusText[0]) {
                            document.getElementById("aprovedComments").innerHTML = ~~document.getElementById("aprovedComments").innerHTML - 1;
                            document.getElementById("waitingComments").innerHTML = ~~document.getElementById("waitingComments").innerHTML + 1;
                            document.getElementById(data).innerHTML = selectedStatus;
                            document.getElementById("status").dataset.status = selectedStatus;
                            document.getElementById(String(data)).parentElement.classList.toggle("unread")
                        } else {
                            document.getElementById("aprovedComments").innerHTML = ~~document.getElementById("aprovedComments").innerHTML + 1;
                            document.getElementById("waitingComments").innerHTML = ~~document.getElementById("waitingComments").innerHTML - 1;
                            document.getElementById(data).innerHTML = selectedStatus;
                            document.getElementById("status").dataset.status = selectedStatus;
                            document.getElementById(String(data)).parentElement.classList.toggle("unread")
                        }
                        document.getElementById("editPopup").classList.toggle("visible");
                    }
                })
            break;
        case "deleteButton":
            //delete comment
            e.preventDefault();
            let formData1 = new FormData(e.currentTarget);
            const url1 = e.submitter.dataset.url + document.getElementById("idComment").value;
            const options1 = {
                method: "post",
                body: JSON.stringify(Object.fromEntries(formData1))
            }
            fetch(url1, options1).then(data => data.json()).then(data => {
                let originalStatus = document.getElementById("status").dataset.status;
                document.getElementById(data).parentElement.remove();
                if (originalStatus == 0) {
                    document.getElementById("waitingComments").innerHTML = ~~document.getElementById("waitingComments").innerHTML - 1;

                } else {
                    document.getElementById("aprovedComments").innerHTML = ~~document.getElementById("aprovedComments").innerHTML - 1;
                }
                document.getElementById("editPopup").classList.toggle("visible");
            })
            break;
    }
})