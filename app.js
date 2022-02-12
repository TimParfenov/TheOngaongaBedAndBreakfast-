// Date picker 
$( function() {
    var dateFormat = "mm/dd/yy",
      from = $( "#from" )
        .datepicker({
          defaultDate: "+1w",
          changeMonth: true,
          numberOfMonths: 2
        })
        .on( "change", function() {
          to.datepicker( "option", "minDate", getDate( this ) );
        }),
      to = $( "#to" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 2
      })
      .on( "change", function() {
        from.datepicker( "option", "maxDate", getDate( this ) );
      });
    function getDate( element ) {
      var date;
      try {
        date = $.datepicker.parseDate( dateFormat, element.value );
      } catch( error ) {
        date = null;
      }
      return date;
    }
  } );

// Room search 
function searchResult(checkin2,checkout2) {
    if (checkin2.length==0 || checkout2.length==0) {
      return;
    }
    xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
      if (this.readyState==4 && this.status==200) {
      //take JSON text from the server and convert it to JavaScript objects
      //rooms will become a two dimensional array of our customers much like 
      //a PHP associative array           
      var rooms = JSON.parse(this.responseText);   
        var tbl = document.getElementById("tblrooms"); //find the table in the HTML
        
        //clear any existing rows from any previous searches
        //if this is not cleared rows will just keep being added
        var rowCount = tbl.rows.length;
        for (var i = 1; i < rowCount; i++) {
           //delete from the top - row 0 is the table header we keep
          tbl.deleteRow(1); 
        }      
        //populate the table
        //mbrs.length is the size of our array
        for (var i = 0; i < rooms.length; i++) {
          var room_id = rooms[i]['roomID'];
          var room_name = rooms[i]['roomname'];
          var room_type = rooms[i]['roomtype'];
          var room_beds = rooms[i]['beds'];
           //create a table row with four cells  
          tr = tbl.insertRow(-1);
          var tabCell = tr.insertCell(-1);
               tabCell.innerHTML = room_id; //roomID
          var tabCell = tr.insertCell(-1);
               tabCell.innerHTML = room_name; //roomname   
          var tabCell = tr.insertCell(-1);
               tabCell.innerHTML = room_type; //roomtype   
          var tabCell = tr.insertCell(-1);
               tabCell.innerHTML = room_beds; //beds   
          }
      }
    }
  //call our php file that will look for a customer or customers matchign the seachstring
  xmlhttp.open("GET","room_search.php?d1="+checkin2+"&d2="+checkout2, true);
  xmlhttp.send();
}

// Customer search 
function searchResults(searchstr) {
  if (searchstr.length==0) {

    return;
  }
  xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
    //take JSON text from the server and convert it to JavaScript objects
    //mbrs will become a two dimensional array of our customers much like 
    //a PHP associative array
      var mbrs = JSON.parse(this.responseText);              
      var tbl = document.getElementById("tblcustomers"); //find the table in the HTML
      
      //clear any existing rows from any previous searches
      //if this is not cleared rows will just keep being added
      var rowCount = tbl.rows.length;
      for (var i = 1; i < rowCount; i++) {
        //delete from the top - row 0 is the table header we keep
        tbl.deleteRow(1); 
      }      
      
      //populate the table
      //mbrs.length is the size of our array
      for (var i = 0; i < mbrs.length; i++) {
        var mbrid = mbrs[i]['customerID'];
        var fn    = mbrs[i]['firstname'];
        var ln    = mbrs[i]['lastname'];
      
         //concatenate our actions urls into a single string
        var urls  = '<a href="viewcustomer.php?id='+mbrid+'">[view]</a>';
          urls += '<a href="editcustomer.php?id='+mbrid+'">[edit]</a>';
          urls += '<a href="deletecustomer.php?id='+mbrid+'">[delete]</a>';

        //create a table row with three cells  
        tr = tbl.insertRow(-1);
        var tabCell = tr.insertCell(-1);
          tabCell.innerHTML = ln; //lastname
        var tabCell = tr.insertCell(-1);
          tabCell.innerHTML = fn; //firstname      
        var tabCell = tr.insertCell(-1);
          tabCell.innerHTML = urls; //action URLS            
      }
    }
  }
  //call our php file that will look for a customer or customers matchign the seachstring
  xmlhttp.open("GET","customersearch.php?sq="+searchstr,true);
  xmlhttp.send();
}

