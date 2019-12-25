function CreateDocument(){

    
    //var title = document.getElementById("docTitle").value;
    //var category1 = document.getElementsByClassName("docCategories")[0].value;
    //var category2 = document.getElementsByClassName("docCategories")[1].value;
    //var category3 = document.getElementsByClassName("docCategories")[2].value;
    var content = document.getElementsByClassName("ql-editor")[0].innerHTML;
    document.getElementById("contentPacker").value = content;
    return;
    //window.location.href = "/?page=createDoc_Execute&title=" + title + "&content=" + content + "&c1=" + category1+ "&c2=" + category2+ "&c3=" + category3;
    $.post("",
    {
        page: "createDoc_Execute",
        title: title,
        contend: content,
        c1: category1,
        c2: category2,
        c3: category3
    },
    function(data,status){
      alert( "Status: " + status);
    });
    return;

    var xhttp;
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        //document.getElementById("txtHint").innerHTML = this.responseText;
        window.location.href = "/myDocs";
      }
    };
    xhttp.open("POST", "gethint.php?q="+str, true);
    xhttp.send(); 
}