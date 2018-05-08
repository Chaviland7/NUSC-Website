function insert_result(resultData) {
  $.post(
    my_ajax_script.ajaxurl,{
      action: 'add_result',
      athlete: resultData.get('athlete'),
      meet: resultData.get('meet'),
      score: resultData.get('score'),
      distance: resultData.get('distance'),
      stroke: resultData.get('stroke'),
      place: resultData.get('place'),
      i_r: resultData.get('i_r')
    },
    function(data){
      console.log(data);
    },
    "html"
  );
  return true;
}