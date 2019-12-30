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

function ChangeCategory(){
  var i1 = document.getElementsByClassName("docDropDownFields")[0].selectedIndex;
  var i2 = document.getElementsByClassName("docDropDownFields")[1].selectedIndex;
  var i3 = document.getElementsByClassName("docDropDownFields")[2].selectedIndex;
  var indexes = [i1, i2, i3];
  var elements = document.getElementsByClassName("docDropDownFields");

  for(var i = 0; i<elements.length; i++){
    for(var j = 0; j<elements[i].options.length; j++){
      elements[i].options[j].disabled = false;
    } 
  }
  
  for(var i = 0; i<elements.length; i++){
    if(elements[i].selectedIndex != 0){
      for(var j = 0; j<elements.length; j++){
        if(i != j){
          elements[j].options[elements[i].selectedIndex].disabled = true;
        }
      }
    }
  }
}