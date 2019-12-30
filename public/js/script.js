function CreateDocument(){
    var content = document.getElementsByClassName("ql-editor")[0].innerHTML;
    document.getElementById("contentPacker").value = content;
    return;
}

function EditDocument(){
  var content = document.getElementsByClassName("ql-editor")[0].innerHTML;
  document.getElementById("contentPacker").value = content;
  return;
}

function SetContent(){
  var value = document.getElementById("contentBank").value;
  document.getElementsByClassName("ql-editor")[0].innerHTML = value;
}