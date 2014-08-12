function reset(){
  console.log(path);
  var post = $.post("/windex/functions/_reset.php", {
    filename: path
  });
};

add_log({'item': data}, type);