/**
 * Created by root on 7/6/15.
 */
function changeOrder(from, sort_by,order,page){
    var oppositeOrder=order=='asc'?'desc':'asc';
    if(from==sort_by){
        document.location.replace('./?page='+page+'&sort_by='+sort_by+'&order='+oppositeOrder);
    } else {
        document.location.replace('./?page=0&sort_by='+from+'&order=asc');
    }
}