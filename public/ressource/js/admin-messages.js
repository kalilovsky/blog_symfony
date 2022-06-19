Array.from(document.getElementsByClassName("editContent")).forEach(e => {
    e.addEventListener("click", () => {
        const url = e.dataset.url;
        let formData = new FormData();
        formData.append("idMessage", e.dataset.id);
        const dataToSend = {
            method: "POST",
            body: formData
        }
        document.getElementById("editPopup").classList.toggle("visible");
        fetch(url, dataToSend)
            .then(data => data.json())
            .then(data => {
                let statusText = ["Non Lu","Lu"];
                let statusMsgTxt = document.getElementById(data.id).innerHTML;
                document.getElementById("content").value  = data.content;
                document.getElementById("email").innerHTML  = data.senderemail;
                document.getElementById("date").innerHTML  = data.date;
                if (statusMsgTxt != statusText[data.status]){
                    document.getElementById(data.id).innerHTML=statusText[data.status];
                    document.getElementById(data.id).parentElement.classList.remove("unread");
                    document.getElementById("unreadMsg").innerHTML = ~~document.getElementById("unreadMsg").innerHTML -1;
                    document.getElementById("readMsg").innerHTML = ~~document.getElementById("readMsg").innerHTML +1;
                }
               
                
            });

    })
})
document.getElementById("popUpCloseBtn").addEventListener("click", () => {
    document.getElementById("editPopup").classList.toggle("visible");
})
window.onclick = function(e) {
    if (e.target == document.getElementById("editPopup")) {
        document.getElementById("editPopup").classList.remove("visible");
    }
}