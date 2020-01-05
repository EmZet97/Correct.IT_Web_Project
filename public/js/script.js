function CreateDocument(){
    var content = document.getElementsByClassName("ql-editor")[0].innerHTML;
    document.getElementById("contentPacker").value = content;
    return;
}

function CorrectDocument(){
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

var last = 1;

function starEnter(index){
  var stars = document.getElementsByClassName("fa-star");
  //starLeave();
  for(var i = 0; i<= index; i++){
    stars[i].classList.remove("far");
    stars[i].classList.add("fas");
  }

  for(var i = index + 1; i< stars.length; i++){
    stars[i].classList.remove("fas");
    stars[i].classList.add("far");
  }

}

function starLeave(){
  var stars = document.getElementsByClassName("fa-star");

  for(var i = 0; i<= last; i++){
    stars[i].classList.remove("far");
    stars[i].classList.add("fas");
  }

  for(var i = last + 1; i< stars.length; i++){
    stars[i].classList.remove("fas");
    stars[i].classList.add("far");
  }

  
}

function starClick(index){
  last = index;
  document.getElementById("last").innerHTML = index;
  document.getElementById("starsPacker").value = index;
}

function SetComment(){
  var comment = document.getElementById("commentBank").value;
  var rate = parseInt(document.getElementById("rateBank").value);

  document.getElementsByClassName("ql-editor")[0].innerHTML = comment;
  last = rate;
  starClick(rate);
  starLeave();
}

function SaveMyDoc(){
  var content = document.getElementsByClassName("ql-editor")[0].innerHTML;
  var userId = document.getElementById("userIdPacker").value;
  var docId = document.getElementById("docIdPacker").value;

  //alert("uid" + userId + " - did" + docId + " - con" + content);
  apiUrl = 'http://localhost';
  //alert("test");
  $.ajax({
    url :  '/?page=saveDoc_Execute',
    method : "POST",
    data : {
      content :  content,
      docID : docId,
      userID : userId
    }
    ,
    success: function() {
      document.getElementById("save_img").style.display = "inline";
      //alert('Success');
      //getUsers();
    }});


}


function typeText(){
  document.getElementById("save_img").style.display = "none";
      
}