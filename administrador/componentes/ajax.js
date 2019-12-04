window.onload = function(){
  document.getElementById('username').addEventListener('keyup', function(){
      ValidaUsuario();
  });
}

function ValidaUsuario(){
  request = new XMLHttpRequest();
    request.onreadystatechange = function(){
      if (this.readyState == 4 && this.status == 200){

        if(request.responseText == 1){
          document.getElementById('username').classList.add('invalid');
          document.getElementById('username').classList.remove('valid');
          document.getElementById("adicionar").disabled = true;
        }else{
          document.getElementById('username').classList.remove('invalid');
          document.getElementById('username').classList.add('valid');
          document.getElementById("adicionar").disabled = false;
        }
    }
  }

  username = document.getElementById('username').value;
  request.open('GET', '../acao.php?acao=ValidarUsuario&username='+username, true);
  request.send();

}
