window.onload = function(){
  document.getElementById('username').addEventListener('keyup', function(){
      ValidaUsuario();
  });
}

function ValidaUsuario(){
  ajax = new XMLHttpRequest();
    ajax.onreadystagechange = function(){
    if(ajax.status == 200){
      if(ajax.responseText == 1){
        document.getElementById('username').classList.add('invalid');
        document.getElementById('username').classList.remove('valid');
      }else{
        document.getElementById('username').classList.remove('invalid');
        document.getElementById('username').classList.add('valid');
      }
    }
  }

  username = document.getElementById('username').value;
  ajax.open('GET', '../acao.php?acao=ValidarUsuario&username='+username, true);
  ajax.send();

}
