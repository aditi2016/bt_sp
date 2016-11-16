function genericEmptyFieldValidator(fields){
  returnBool = true;
  $.each(fields, function( index, value ) {
    console.log(value);
    if($('#'+value).val() == "" || $('#'+value).val() == null || $('#'+value).val() == " "){
      $('#'+value).keypress(function() {
        genericEmptyFieldValidator([value]);
      });
      $('#'+value).css("border-color", "red");
      returnBool = false;
    }
    else {
      $('#'+value).css("border-color", "blue");
    }
  });
  return returnBool;
}

function validateEmail(fld) {
  var error="";
  var tfld = trim(fld);                        // value of field with whitespace trimmed off
  var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/ ;
  if (!emailFilter.test(tfld)) {              //test email for illegal characters
    return false;
  } 
  else {
    return true;
  }
}

String.prototype.isValidDate = function() {
  var IsoDateRe = new RegExp("^([0-9]{2})/([0-9]{2})/([0-9]{4})$");
  var matches = IsoDateRe.exec(this);
  if (!matches) return false;
  else return true ;
  /*var composedDate = new Date(matches[1], (matches[2] - 1), matches[3]);

  return ((composedDate.getMonth() == (matches[2] - 1)) &&
          (composedDate.getDate() == matches[3]) &&
          (composedDate.getFullYear() == matches[1]));*/
}

function validateAge(fld) {              
  var Filter = /^[0-9-+]+$/;
  if (!Filter.test(fld)) return false;
  else if (fld < 16 || fld > 99) return false; 
  else return true; 
}

function validateExperience(fld) {              
  var Filter = /^[0-9-+]+$/;
  if (!Filter.test(fld)) return false;
  else if (fld < 1 || fld > 50) return false; 
  else return true; 
}

function validateTime(time){
  var a=true;
  var time_arr=time.split(":");
  if(time_arr.length!=2)  a=false;
  else {
    if(isNaN(time_arr[0]) || isNaN(time_arr[1])){                
      a=false;
    }
    if(time_arr[0]<24 && time_arr[1]<60) {}
    else a =false;         
  }
  return a;
}

function validatePhone(fld) {    
  var res = fld.split(",");
  var filter = /^([7-9][0-9]{9})+$/;
  var result = "" ;
  for(var i = 0; i < res.length; i++) {
    var stripped = res[i];
    if (stripped.value == "") {
      result = false;
    } 
    else if (!(filter.test(stripped))) {
      result = false ;
    } 
    else if (!(stripped.length == 10)) {
      result = false;
    }
    else result = true ;
  }
  return result;
}

function validateSalary(fld) {
  var valid = (value.match(/^-?\d*(\.\d+)?$/));
  if (!valid.test(fld)) return false;
  else if (fld == "" || fld == " " || fld == null) return false;
  else return true; 
}

function nospaces(t){
  if(t.value.match(/\s/g)){
    alert('Sorry, you are not allowed to enter any spaces');
    t.value=t.value.replace(/\s/g,'');
  }
}

function validateRequestSearch() {
  var dataString = "";
  var gender = $('#gender').val();
  var work_time = $('#work_time').val();
  var salary = $('#salary').val();
  var skill = $('#skill').val();
  var area = $('#area').val();
  dataString = "skill=" + skill + "&gender=" + gender + "&work_time=" + work_time + "&salary=" + salary + "&area=" + area;
  $.ajax({
    type: "POST",
    url: "ajax/searchRequest.php",
    data: dataString,
    cache: false,
    success: function(result){
      $(".searchresult").html('');
      $(".searchresult").append(result);
    }
  });
  return false;
}

function trim(s){
  return s.replace(/^\s+|\s+$/, '');
}

function postWorkerDetails(fields,languagesArray,skillsArray,request_id,id,servicesArray,police,work_time,gender,workerareasArray,newskill,newworkerarea) {
  var dataString = "";
  var d = $('#'+fields[9]).val().split('/');   
  var date = d[2] +'-'+ d[0] +'-'+ d[1];
  dataString = "first_name=" + $('#'+fields[0]).val() + "&last_name=" + $('#'+fields[1]).val() + "&mobile=" +  $('#'+fields[2]).val() + 
      "&emergancy_mobile=" +  $('#'+fields[3]).val() + "&age=" +  $('#'+fields[4]).val() + "&current_address=" + $('#'+fields[5]).val() + 
      "&parmanent_address=" +  $('#'+fields[6]).val() + "&education=" + $('#'+fields[7]).val() + "&experience=" + $('#'+fields[8]).val()+ 
      "&birth_date=" + date + "&remarks=" + $('#'+fields[10]).val() + "&newskill=" + newskill + "&services=" + servicesArray + 
      "&languages=" + languagesArray + "&skills=" + skillsArray + "&request_id=" + request_id + "&type=" + id + "&gender=" + gender + 
      "&timing=" + $('#'+fields[12]).val() + "&timing2=" + $('#'+fields[13]).val() + "&salary=" +  $('#'+fields[14]).val() + "&salary2=" + 
      $('#'+fields[15]).val() + "&work_time="+work_time+"&police="+police+"&worker_area=" + workerareasArray + "&newworkerarea=" + newworkerarea;
      /*"&address_proof_name=" + $('#'+fields[2]).val() + "&address_proof_id=" + $('#'+fields[3]).val() + 
      "&id_proof_name=" + $('#'+fields[4]).val() + "&id_proof_id=" +  $('#'+fields[5]).val() + */
  console.log(dataString);
  if(validatePhone($('#'+fields[2]).val()) == false) alert('Enter valid Phone Number');
  else if(validatePhone($('#'+fields[3]).val()) == false) alert('Enter valid Emergency Phone Number');
  else if(!($('#'+fields[9]).val().isValidDate())) alert('Enter valid Birth date');
  else if(validateAge($('#'+fields[4]).val())==false) alert('Enter valid age');
  else if(validateExperience($('#'+fields[8]).val())==false) alert('Enter valid Experience');
  else {
    $.ajax({
      type: "POST",
      url: "ajax/addWorker.php",
      data: dataString,
      cache: false,
      success: function(result){
        alert("Added Successfully");
        $(fields).each(function(i, idVal){ 
          $("#"+idVal).val(""); 
        });
        $('#languages').val("");
        $('#skills').val("");
      },
      error: function(result){
        alert("result");
        return false;
      }
    });
    return false;
  }
}

function validateWorkerDetails(request_id, id){
  if(id == 1) {
    fields = ["first_name"+request_id,"last_name"+request_id, "mobile"+request_id, "emergancy_mobile"+request_id, "age"+request_id,  
            "current_address"+request_id, "parmanent_address"+request_id, "education"+request_id, "experience"+request_id,"birth_date"+request_id, 
            "remarks"+request_id, "services"+request_id,"timing"+request_id,"timing2"+request_id,"salary"+request_id,"salary2"+request_id];
            //"address_proof_name"+request_id, "address_proof_id"+request_id, "id_proof_name"+request_id, "id_proof_id"+request_id,
    var languagesArray = []; 
    $('#languages'+request_id).each(function(i, selected){ 
      languagesArray[i] = $(selected).val(); 
    });
    var servicesArray = []; 
    $('#services'+request_id).each(function(i, selected){ 
      servicesArray[i] = $(selected).val(); 
    });
    var skillsArray = [];
    $(".values").each(function(i){
        skillsArray[i] = $(this).data('value');
    });
    var workerareasArray = [];
    $(".workerareavalues").each(function(i){
        workerareasArray[i] = $(this).data('value');
    });
    var newworkerarea = $('#worker_area'+request_id).val();
    var newskill = $('#newskill'+request_id).val();
    var time = $('#timing'+request_id).val();
    var time2 = $('#timing2'+request_id).val();
    var salary = $('#salary'+request_id).val();
    var salary2 = $('#salary2'+request_id).val();
    var work_time = $('#work_time'+request_id).val();
    var gender = $('#gender'+request_id).val();
    var police = $('#police'+request_id).val();
  }
  else if(id == 2){
    fields = ["2first_name"+request_id,"2last_name"+request_id, "2mobile"+request_id, "2emergancy_mobile"+request_id, "2age"+request_id,  
            "2current_address"+request_id, "2parmanent_address"+request_id, "2education"+request_id, "2experience"+request_id,"2birth_date"+request_id,
            "2remarks"+request_id, "2services"+request_id,"2timing"+request_id,"2timing2"+request_id,"2salary"+request_id,"2salary2"+request_id];
            //"2address_proof_name"+request_id, "2address_proof_id"+request_id,"2id_proof_name"+request_id, "2id_proof_id"+request_id,
    var languagesArray = []; 
    $('#2languages'+request_id).each(function(i, selected){ 
      languagesArray[i] = $(selected).val(); 
    });
    var servicesArray = []; 
    $('#2services'+request_id).each(function(i, selected){ 
      servicesArray[i] = $(selected).val(); 
    });
    var skillsArray = [];
    $(".values").each(function(i){
        skillsArray[i] = $(this).data('value');
    });
    var workerareasArray = [];
    $(".workerareavalues").each(function(i){
        workerareasArray[i] = $(this).data('value');
    });
    var newworkerarea = $('#2worker_area'+request_id).val();
    var newskill = $('#2newskill'+request_id).val();
    var time = $('#2timing'+request_id).val();
    var time2 = $('#2timing2'+request_id).val();
    var salary = $('#2salary'+request_id).val();
    var salary2 = $('#2salary2'+request_id).val();
    var work_time = $('#2work_time'+request_id).val();
    var gender = $('#2gender'+request_id).val();
    var police = $('#2police'+request_id).val();
  }
  else {
    fields = ["first_name","last_name", "mobile", "emergancy_mobile", "age", "current_address", "parmanent_address", "education", 
            "experience", "birth_date", "remarks", "services","timing","timing2","salary","salary2"];
            //"address_proof_name", "address_proof_id", "id_proof_name", "id_proof_id",
    var languagesArray = []; 
    $('#languages').each(function(i, selected){ 
      languagesArray[i] = $(selected).val(); 
    });
    var servicesArray = []; 
    $('#services').each(function(i, selected){ 
      servicesArray[i] = $(selected).val(); 
    });
    var skillsArray = [];
    $(".values").each(function(i){
        skillsArray[i] = $(this).data('value');
    });
    var workerareasArray = [];
    $(".workerareavalues").each(function(i){
        workerareasArray[i] = $(this).data('value');
    });
    var newworkerarea = $('#worker_area').val();
    var newskill = $('#newskill').val();
    var time = $('#timing').val();
    var time2 = $('#timing2').val();
    var salary = $('#salary').val();
    var salary2 = $('#salary2').val();
    var work_time = $('#work_time').val();
    var gender = $('#gender').val();
    var police = $('#police').val();
  }
  if(genericEmptyFieldValidator(fields)){
    var x = document.getElementsByClassName("values").length;
    var c = document.getElementsByClassName("workerareavalues").length;
    var diff1 = time.split(':')[0];
    var diff2 = time2.split(':')[0];
    if(time == 0 || time2 == 0 || parseInt(time2) < parseInt(time)){
      alert('Enter valid work timing');
    }
    else if(salary == 0 || salary2 == 0 || parseInt(salary2) < parseInt(salary)){
      alert('Enter valid salary');
    }
    else if(work_time == 0){
      alert('Enter valid working time');
    }
    else if((parseInt(diff2)-parseInt(diff1)) != work_time)  alert('Please check worker timings and working time accordingly');
    else if(x == 0 && (newskill == "" || newskill == " " || newskill == null)){
      alert('Please enter or select a Skill');
    }
    else if(c == 0 && (newworkerarea == "" || newworkerarea == " " || newworkerarea == null)){
      alert('Please enter or select a Worker Area');
    }
    else {
      postWorkerDetails(fields,languagesArray,skillsArray,request_id,id,servicesArray,police,work_time,gender,workerareasArray,newskill,newworkerarea);
    }
  }
  return false;
}

function postRequestDeatils(fields,skillsArray,areasArray,workerareasArray,status,servicesArray,work_time,gender,newskill,newarea,newworkerarea,priority) {
  var dataString = "";
  var d = $('#'+fields[3]).val().split('/');   
  var date = d[2] +'-'+ d[0] +'-'+ d[1];
  dataString = "name=" + $('#'+fields[0]).val() + "&mobile=" + $('#'+fields[1]).val() + "&address=" + $('#'+fields[2]).val() + "&area=" +  areasArray 
      + "&newarea=" + newarea + "&created_time=" + date + "&remarks=" + $('#'+fields[4]).val() +"&timing=" + $('#'+fields[5]).val() + "&new_status=" 
      + status + "&gender=" +  gender + "&salary=" +  $('#'+fields[7]).val() + "&work_time=" +  work_time + "&salary2=" +  $('#'+fields[8]).val() + 
      "&worker_area=" + workerareasArray + "&newworkerarea=" + newworkerarea + "&services=" + servicesArray + "&skills=" + skillsArray + "&newskill="
      + newskill + "&timing2=" + $('#'+fields[6]).val() + "&priority=" + priority;

  if(validatePhone($('#'+fields[1]).val()) == false){
    alert('Enter valid Phone Number');
  }
  else if(!($('#'+fields[3]).val().isValidDate())) alert('Enter valid date');
  else if(validateTime($('#'+fields[5]).val())== false) alert('Enter valid Time');
  else if(validateTime($('#'+fields[6]).val())== false) alert('Enter valid Time');
  //else if(validateSalary(salary)== false) alert('Enter valid Salary');
  //else if(validateSalary(salary2)== false) alert('Enter valid Salary');
  else {
    $.ajax({

      type: "POST",
      url: "ajax/addRequest.php",
      data: dataString,
      cache: false,
      success: function(result){
        alert("Added Successfully"),
        $(fields).each(function(i, idVal){ 
          $("#"+idVal).val(""); 
        });
        $('#worker_area').val("");
        $('#newskill').val("");
        $('#newarea').val("");
        $('.values').remove();
        $('.areavalues').remove();
        $('.workerareavalues').remove();
        var checkboxes = document.getElementsByTagName('input');
        for (var i = 0; i < checkboxes.length; i++) {
          checkboxes[i].checked = false;
        };
        return false;
      },
      error: function(result){
        alert(result);
        return false;
      }

    });
    return false;
  }
}

function validateRequestDetails(){
  
  fields = ["name","mobile","address","created_time","remarks","timing","timing2","salary","salary2"];
  
  var servicesArray = []; 
  $('input[name=skill]:checked').each(function(i, checked){ 
    servicesArray[i] = $(checked).val(); 
  });
  var skillsArray = [];
  $(".values").each(function(i){
      skillsArray[i] = $(this).data('value');
  });
  var areasArray = [];
  $(".areavalues").each(function(i){
      areasArray[i] = $(this).data('value');
  });
  var workerareasArray = [];
  $(".workerareavalues").each(function(i){
      workerareasArray[i] = $(this).data('value');
  });
  var newskill = $('#newskill').val();
  var newarea = $('#newarea').val();
  var newworkerarea = $('#worker_area').val();
  var status = $("#new_status").val();
  var time = $('#timing').val();
  var time2 = $('#timing2').val();
  var salary = $('#salary').val();
  var salary2 = $('#salary2').val();
  var work_time = $('#work_time').val();
  var gender = $('#gender').val();
  var priority = $('#priority').val();
  if(genericEmptyFieldValidator(fields)){
    var a = document.getElementsByClassName("values").length;
    var b = document.getElementsByClassName("areavalues").length;
    var c = document.getElementsByClassName("workerareavalues").length;
    var diff1 = time.split(':')[0];
    var diff2 = time2.split(':')[0];
    if(time == 0 || time2 == 0 || parseInt(time2) < parseInt(time)){
      alert('Enter valid work timing');
    }
    else if(salary == 0 || salary2 == 0 || parseInt(salary2) < parseInt(salary)){
      alert('Enter valid salary');
    }
    else if(work_time == 0){
      alert('Enter valid working time');
    }
    else if((parseInt(diff2)-parseInt(diff1)) != work_time)  alert('Please check worker timings and working time accordingly');
    else if(a == 0 && (newskill == "" || newskill == " " || newskill == null)){
      alert('Please enter or select a Skill');
    }
    else if(b == 0 && (newarea == "" || newarea == " " || newarea == null)){
      alert('Please enter or select a Area');
    }
    else if(c == 0 && (newworkerarea == "" || newworkerarea == " " || newworkerarea == null)){
      alert('Please enter or select a Worker Area');
    }
    else if(servicesArray == null || servicesArray == ""){
      alert('Please select a Requirement');
    }
    else {
      postRequestDeatils(fields,skillsArray,areasArray,workerareasArray,status,servicesArray,work_time,gender,newskill,newarea,newworkerarea,priority);
    }
  }
  return false;
}

function postUserDeatils(fields, type, head){
  var dataString = "";
  dataString = "first_name=" + $('#'+fields[0]).val() + "&last_name=" + $('#'+fields[1]).val() + "&email=" + $('#'+fields[2]).val() + 
      "&phone=" + $('#'+fields[3]).val() + "&employee_type=" + type + "&salary=" +  $('#'+fields[4]).val() + 
      "&password=" +  $('#'+fields[5]).val() + "&head=" + head ;
  if($('#'+fields[0]).val().length < 3){
    alert("First Name is too short");
  }
  else if($('#'+fields[1]).val().length < 3){
    alert("Last Name is too short");
  }
  else if(validateEmail($('#'+fields[2]).val()) == false){
    alert("Enter valid email");
  }
  else if(validatePhone($('#'+fields[3]).val()) == false){
    alert("Enter valid phone number");
  }
  else if($('#'+fields[4]).val().length < 3){
    alert("Enter valid Base salary");
  }
  else if($('#'+fields[5]).val().length < 6){
    alert("Password length should be more than 6 chars");
  }
  else if($('#'+fields[5]).val() == $('#'+fields[6]).val()){ 
    $.ajax({
      type: "POST",
      url: "ajax/addUser.php",
      data: dataString,
      cache: false,
      success: function(result){
        
        $(fields).each(function(i, idVal){ 
          $("#"+idVal).val(""); 
        });
        alert("Added Successfully");
      },
      error: function(result){
        alert(result);
        return false;
      }
    });
  }
  else {
    alert("Password do not match");
  }
}

function validateUserDetails(){
  fields = ["first_name","last_name","email","phone", "salary", "password","password2"];
  var type = $('#employee_type').val();
  var head = $('#teamHead').val();
  if(head != 0){
    if(genericEmptyFieldValidator(fields)){
      postUserDeatils(fields, type, head);
    }
    return false;
  }
  else alert('Please Select Team Head');
}

function postMeetingDeatils(fields, id, worker) {

  var dataString = "";
  var d = $('#'+fields[0]).val().split('/');   
  var date = d[2] +'-'+ d[0] +'-'+ d[1];
  dataString = "remark=" + $('#'+fields[2]).val() + "&date=" + date+" "+$('#'+fields[1]).val() + ":00" 
                + "&worker=" + worker + "&id=" + id ;
  if(!($('#'+fields[0]).val().isValidDate())) alert('Enter valid date'); 
  else if(validateTime($('#'+fields[1]).val())==false) alert('Enter valid Time');
  else {
    $.ajax({
      type: "POST",
      url: "ajax/addMeeting.php",
      data: dataString,
      cache: false,
      success: function(result){
        $(fields).each(function(i, idVal){ 
          $("#"+idVal).val(""); 
        });      
        alert("Added Successfully");
      },
      error: function(result){
        alert(result);
        return false;
      }
    });
  }
}

function validateMeetingDetails(id){
  
  fields = ["date"+id,"time"+id,"remark"+id];
  var worker = $('#worker'+id).val();
  if(genericEmptyFieldValidator(fields)){
    postMeetingDeatils(fields, id, worker);
  }
  return false;
}

function workerDetails(id, type){
  $.ajax({
    type: "POST",
    url: "ajax/workerDetails.php",
    data: "id="+ id + "&type=" + type,
    cache: false,
    success: function(result){
      //alert(result);
      $("#workerform_"+id).show().html(result); 
    },
    error: function(result){
      alert("Error Occured");
      return false;
    }
  });
}

function validateNote(id, type){
  var dataString = "";
  fields = ["note"+id];
  var note = $("#note"+id).val() ;
  if(genericEmptyFieldValidator(fields)){
    dataString = "id=" + id + "&type=" + type + "&note=" + note;
    $.ajax({
      type: "POST",
      url: "ajax/addnote.php",
      data: dataString,
      cache: false,
      success: function(result){
        alert("Added Successfully");
      },
      error: function(result){
        alert(result);
        return false;
      }
    });
  }
  return false;
}

function addnote (id, type) {
  var status = "<form class='form-horizontal' id='note_form"+id+"' onsubmit='return(validateNote("+id+", \""+type+"\"));'>" +   
                  "<div class='form-group'>"+
                    "<label class='col-md-2 control-label'>Note</label>"+
                    "<div class='col-md-8'>"+
                      "<textarea type='text' id='note"+id+"' class='form-control' placeholder='Note' rows='3'></textarea>"+
                    "</div>"+
                  "</div>"+
                  "<div class='form-group'>"+
                    "<label class='col-md-3 control-label'></label>"+
                    "<div class='col-md-7'>"+
                      "<button type='submit' class='btn btn-success pull-right' >Submit</button>"+
                    "</div>"+
                  "</div>"+
                "</form>";
  $("#workerform_"+id).show().html(status);
}

function validateFeedback(id, type){
  var dataString = "";
  var feedback = $("#feedback"+id).val() ;
  if(genericEmptyFieldValidator(feedback)){
    dataString = "id=" + id + "&type=" + type + "&feedback=" + feedback;
    $.ajax({
      type: "POST",
      url: "ajax/addfeedback.php",
      data: dataString,
      cache: false,
      success: function(result){
        alert("Added Successfully");
      },
      error: function(result){
        alert(result);
        return false;
      }
    });
    return false;
  }
  return false;
}

function feedback(id, type){
  var feedback = "<form class='form-horizontal' id='feedback_form"+id+"' onsubmit='return(validateFeedback("+id+", \""+type+"\"));'>" +   
                    "<div class='form-group'>"+
                      "<label class='col-md-2 control-label'>Feedback</label>"+
                      "<div class='col-md-8'>"+
                        "<textarea type='text' id='feedback"+id+"' class='form-control' placeholder='Feedback' rows='4'></textarea>"+
                      "</div>"+
                    "</div>"+
                    "<div class='form-group'>"+
                      "<label class='col-md-3 control-label'></label>"+
                      "<div class='col-md-7'>"+
                        "<button type='submit' class='btn btn-success pull-right' >Submit</button>"+
                      "</div>"+
                    "</div>"+
                  "</form>";
  $("#workerform_"+id).show().html(feedback);
}

function validateStatus(id, oldStatus) {
  var dataString = "";
  var newStatus = $("#new_status"+id).val() ;
  if(oldStatus == 'demo'){
    var salary = $('#salary'+id).val();
    if(isFinite(salary) && salary != ''){
      dataString = "sr_id=" + id + "&old_status=" + oldStatus + "&new_status=" + newStatus + "&salary=" + salary;
      postStatus(dataString);
    }
    else alert("Enter valid Salary");
    return false;
  }
  else {
    dataString = "sr_id=" + id + "&old_status=" + oldStatus + "&new_status=" + newStatus;
    postStatus(dataString);
  }
}

function postStatus(dataString) {
  $.ajax({
    type: "POST",
    url: "ajax/ChangeStatus.php",
    data: dataString,
    cache: false,
    success: function(result){
      alert("Changed Successfully");
      requestData("this");
    },
    error: function(result){
      alert(result);
      return false;
    }
  });
}

function getselectedarea(id, type) {
  if(type == 1) var area = $('#workerareas'+id).val();
  else if(type == 2) var area = $('#2workerareas'+id).val();
  else if(type == 3) var area = $('#areas').val();
  else var area = $('#workerareas').val();
  $.ajax({
    type: "POST",
    url: "ajax/areas.php",
    data: "area="+area+"&type="+type,
    cache: false,
    success: function(result){
      if(type == 1) $("#selectedworkerareas"+id).append(result);
      else if(type == 2) $("#2selectedworkerareas"+id).append(result);
      else if(type == 3) $("#selectedareas").append(result);
      else $("#selectedworkerareas").append(result);
    },
    error: function(result){
      return false;
    }
  }); 
}

function getselectedskill(id, type) {
  if(type == 1) var skills = $('#skills'+id).val();
  else if(type == 2) var skills = $('#2skills'+id).val();
  else var skills = $('#skills').val();
  $.ajax({
    type: "POST",
    url: "ajax/skill.php",
    data: "skills="+skills ,
    cache: false,
    success: function(result){
      if(type == 1) $("#selectedskills"+id).append(result);
      else if(type == 2) $("#2selectedskills"+id).append(result);
      else $("#selectedskills").append(result);
    },
    error: function(result){
      return false;
    }
  }); 
}

function deleteskill(skil, id, type) {
  $.ajax({
    type: "POST",
    url: "ajax/deleteSkills.php",
    data: "type="+type + "&id=" +id + "&skill="+skil,
    cache: false,
    success: function(result){
      $('#'+skil).remove();
    }
  }); 
}

function getSkills(id, type) {
  $.ajax({
    type: "POST",
    url: "ajax/getSkills.php",
    data: "type="+type + "&id=" +id ,
    cache: false,
    success: function(result){
      $("#selectedskills").append(result);
    }
  }); 
}

function getAreas(id) {
  $.ajax({
    type: "POST",
    url: "ajax/getSelectedAreas.php",
    data: "sr_id=" +id ,
    cache: false,
    success: function(result){
      $("#selectedareas").append(result);
    }
  }); 
}

function getWorkerAreas(id) {
  $.ajax({
    type: "POST",
    url: "ajax/getSelectedWorkerAreas.php",
    data: "sr_id=" +id ,
    cache: false,
    success: function(result){
      $("#selectedworkerareas").append(result);
    }
  }); 
}

function changeStatus(id, oldStatus, type){
  if(type == 1){
    var status = "<form class='form-horizontal' id='status_form"+id+"' onsubmit='return(validateStatus("+id+", \""+oldStatus+"\"));'>" +   
                    "<div class='form-group'>"+
                      "<label class='col-md-3 control-label'>Status</label>"+
                      "<div class='col-md-3'>"+
                        "<select class='selectpicker' id='new_status"+id+"' data-live-search='true' data-width='100%'>" +   
                          "<option value='open'>Open </option>"+
                          "<option value='salary_issue'>Salary Issues</option>"+
                          "<option value='not_interested'>Not Interested</option>"+
                          "<option value='just_to_know'>For information Purpose</option>"+
                          "<option value='decay'>Decay</option>"+
                          "<option value='me_open'>Search Worker</option>"+
                          "<option value='followback'>Followback</option>"+
                        "</select>"+
                      "</div>"+
                    "</div>"+
                    "<div class='form-group'>"+
                      "<label class='col-md-3 control-label'></label>"+
                      "<div class='col-md-7'>"+
                        "<button type='submit' class='btn btn-success pull-right' >Submit</button>"+
                      "</div>"+
                    "</div>"+
                  "</form>";
    $("#workerform_"+id).show().html(status);
  }
  else if(type == 2) {
    var status = "<form class='form-horizontal' id='status_form"+id+"' onsubmit='return(validateStatus("+id+", \""+oldStatus+"\"));'>" +   
                    "<div class='form-group'>"+
                      "<label class='col-md-2 control-label'>Status</label>"+
                      "<div class='col-md-4'>"+
                        "<select class='selectpicker' id='new_status"+id+"' data-live-search='true' data-width='100%'>" +   
                          "<option value='open'>Open </option>"+
                          "<option value='salary_issue'>Salary Issues</option>"+
                          "<option value='not_interested'>Not Interested</option>"+
                          "<option value='just_to_know'>For information Purpose</option>"+
                          "<option value='me_open'>Search Worker</option>"+
                          "<option value='decay'>Decay</option>"+
                          "<option value='demo'>Demo</option>"+
                          "<option value='done'>Done</option>"+
                        "</select>"+
                      "</div>"+
                      "<label class='col-md-3 control-label'>Salary</label>"+
                      "<div class='col-md-3'>"+
                        "<input type='text' id ='salary"+id+"' class='form-control' placeholder='Enter Fixed Salary' />"+
                        "<span class='small'>Like 3000 or 10000 </span>"+
                      "</div>"+
                    "</div>"+
                    "<div class='form-group'>"+
                      "<label class='col-md-3 control-label'></label>"+
                      "<div class='col-md-7'>"+
                        "<button type='submit' class='btn btn-success pull-right' >Submit</button>"+
                      "</div>"+
                    "</div>"+
                  "</form>";
    $("#workerform_"+id).show().html(status);
  }
  else if(type == 3) {
    var status = "<form class='form-horizontal' id='status_form"+id+"' onsubmit='return(validateStatus("+id+", \""+oldStatus+"\"));'>" +   
                    "<div class='form-group'>"+
                      "<label class='col-md-3 control-label'>Status</label>"+
                      "<div class='col-md-3'>"+
                        "<select class='selectpicker' id='new_status"+id+"' data-live-search='true' data-width='100%'>" +   
                          "<option value='open'>Open </option>"+
                          "<option value='salary_issue'>Salary Issues</option>"+
                          "<option value='not_interested'>Not Interested</option>"+
                          "<option value='just_to_know'>For information Purpose</option>"+
                          "<option value='me_open'>Search Worker</option>"+
                          "<option value='decay'>Decay</option>"+
                          "<option value='demo'>Demo</option>"+
                          "<option value='done'>Done</option>"+
                          "<option value='delete'>Delete</option>"+
                          "<option value='followback'>Followback</option>"+
                          "<option value='feedback'>Feedback</option>"+
                        "</select>"+
                      "</div>"+
                      "<label class='col-md-3 control-label'>Salary</label>"+
                      "<div class='col-md-3'>"+
                        "<input type='text' id ='salary"+id+"' class='form-control' placeholder='Enter Fixed Salary' />"+
                      "</div>"+
                    "</div>"+
                    "<div class='form-group'>"+
                      "<label class='col-md-3 control-label'></label>"+
                      "<div class='col-md-7'>"+
                        "<button type='submit' class='btn btn-success pull-right' >Submit</button>"+
                      "</div>"+
                    "</div>"+
                  "</form>";
    $("#workerform_"+id).show().html(status);
  }
  else {}
}

function postDeatils(fields,skillsArray,areasArray,workerareasArray,servicesArray,work_time,gender,newskill,newarea,newworkerarea,id,priority){
  var dataString = "";
  var d = $('#'+fields[3]).val().split('/');   
  var date = d[2] +'-'+ d[0] +'-'+ d[1];
  dataString = "name=" + $('#'+fields[0]).val() + "&mobile=" + $('#'+fields[1]).val() + "&address=" + $('#'+fields[2]).val() + "&area=" +  areasArray 
      + "&newarea=" + newarea + "&created_time=" + date + "&remarks=" + $('#'+fields[4]).val() +"&timing=" + $('#'+fields[5]).val() + 
      "&new_status=" + status + "&gender=" +  gender + "&salary=" +  $('#'+fields[7]).val() + "&work_time=" +  work_time + "&salary2=" +  $('#'+fields[8]).val()
      + "&worker_area=" + workerareasArray + "&newworkerarea=" + newworkerarea + "&services=" + servicesArray + "&skills=" + skillsArray + 
      "&newskill=" + newskill + "&timing2=" + $('#'+fields[6]).val() + "&sr_id=" + id + "&priority=" + priority; 
  if(validatePhone($('#'+fields[1]).val()) == false){
    alert('Enter valid Phone Number');
  }
  else if(!($('#'+fields[3]).val().isValidDate())) alert('Enter valid date');
  else if(validateTime($('#'+fields[5]).val())== false) alert('Enter valid Time');
  else if(validateTime($('#'+fields[6]).val())== false) alert('Enter valid Time');
  //else if(validateSalary(salary)== false) alert('Enter valid Salary');
  //else if(validateSalary(salary2)== false) alert('Enter valid Salary');
  else {
    $.ajax({

      type: "POST",
      url: "ajax/update.php",
      data: dataString,
      cache: false,
      success: function(result){
        alert("Updated Successfully");
      },
      error: function(result){
        alert(result);
        return false;
      }
    });
    return false;
  }
}

function validateUpdateDetails(id){
  fields = ["name","mobile","address","created_time","remarks","timing","timing2","salary","salary2"];
  
  var servicesArray = []; 
  $('input[name=skill]:checked').each(function(i, checked){ 
    servicesArray[i] = $(checked).val(); 
  });
  var skillsArray = [];
  $(".values").each(function(i){
      skillsArray[i] = $(this).data('value');
  });
  var areasArray = [];
  $(".areavalues").each(function(i){
      areasArray[i] = $(this).data('value');
  });
  var workerareasArray = [];
  $(".workerareavalues").each(function(i){
      workerareasArray[i] = $(this).data('value');
  });
  var newskill = $('#newskill').val();
  var newarea = $('#newarea').val();
  var newworkerarea = $('#worker_area').val();
  var time = $('#timing').val();
  var time2 = $('#timing2').val();
  var salary = $('#salary').val();
  var salary2 = $('#salary2').val();
  var work_time = $('#work_time').val();
  var gender = $('#gender').val();
  var priority = $('#priority').val();
  if(genericEmptyFieldValidator(fields)){
    var a = document.getElementsByClassName("values").length;
    var b = document.getElementsByClassName("areavalues").length;
    var c = document.getElementsByClassName("workerareavalues").length;
    if(time == 0 || time2 == 0 || parseInt(time2) < parseInt(time)){
      alert('Enter valid work timing');
    }
    else if(salary == 0 || salary2 == 0 || parseInt(salary2) < parseInt(salary)){
      alert('Enter valid salary');
    }
    else if(work_time == 0){
      alert('Enter valid working time');
    }
    else if(a == 0 && (newskill == "" || newskill == " " || newskill == null)){
      alert('Please enter or select a Skill');
    }
    else if(b == 0 && (newarea == "" || newarea == " " || newarea == null)){
      alert('Please enter or select a Area');
    }
    else if(c == 0 && (newworkerarea == "" || newworkerarea == " " || newworkerarea == null)){
      alert('Please enter or select a Worker Area');
    }
    else if(servicesArray == null || servicesArray == ""){
      alert('Please select a Requirement');
    }
    else {
      postDeatils(fields,skillsArray,areasArray,workerareasArray,servicesArray,work_time,gender,newskill,newarea,newworkerarea,id,priority);
    }
  }
  return false;
}

function addmeeting(id,type){
  var meeting = "<form class='form-horizontal' id='meeting_details_form"+id+"' onsubmit='return (validateMeetingDetails("+id+"));'>" +
                  "<div class='form-group'>"+
                    "<label class='col-md-2 control-label'>Date</label>"+
                    "<div class='col-md-4'>"+
                      "<input type='text' id ='date"+id+"' class='form-control' placeholder='Enter Date' />"+
                    "</div>"+
                    "<label class='col-md-2 control-label'>Time</label>"+
                    "<div class='col-md-4'>"+
                      "<input type='text' id ='time"+id+"' class='form-control' placeholder='Enter Time ' />"+
                    "</div>"+
                  "</div>"+
                  "<div class='form-group'>"+
                    "<label class='col-md-3 control-label'>Remarks</label>"+
                    "<div class='col-md-3'>"+
                      "<input type='text' id ='remark"+id+"' class='form-control' placeholder='Enter remarks' />"+
                    "</div>"+
                    "<label class='col-md-3 control-label'>Worker</label>"+
                    "<div class='col-md-3'>"+
                      "<select id='worker"+id+"'>";
  if(type == 1 || type == 3){
    var data = "<option value='1' >Worker 1</option><option value='2' >Worker 2</option>";
  }
  else var data = "<option value='0' >Pre Meeting</option>";
  meeting += data +
                      "</select>"+
                    "</div>"+
                  "</div>"+
                  "<div class='form-group'>"+
                    "<label class='col-md-3 control-label'></label>"+
                    "<div class='col-md-7'>"+
                      "<button type='submit' class='btn btn-success pull-right' >Submit Details</button>"+
                    "</div>"+
                  "</div>"+
                "</form>";
  $("#workerform_"+id).show().html(meeting);
  $('#date'+id).datepicker();
  $('#time'+id).timepicker();
}

function viewrequestDetails(status, type, userId) {
  $.ajax({
    type: "POST",
    url: "ajax/employeeRequests.php",
    data: "status="+status +"&type="+type+"&user_id="+userId,
    cache: false,
    success: function(result){
      $("#userDetails_"+userId).show().html(result);
    }
  });
  return false;
}

function mePick(id) {
  bootbox.confirm("Ready for new challange !!!", function(result) {
    if(result){
      $.ajax({
        type: "POST",
        url: "ajax/pick.php",
        data: "request_id="+id,
        cache: false,
        success: function(result){
          requestData("this");
        },
        error: function(result){
          console.log("inside error");
          console.log(result);
          return false;
        }
      });
    }
  });
}

function validatebill(id) {
  var percent = $('#percentage'+id).val();
  var discount = $('#discount'+id).val();
  var type = "request";
  if(percent == 0){
    alert("Please select Percentage");
  }
  else {
    window.open("ajax/bills.php?sr_id="+id+"&percent="+percent+"&type="+type+"&discount="+discount, '_blank')
    return false;
  }
}

function generateBill(id) {
  var meeting = "<form class='form-horizontal' id='bill_form"+id+"' onsubmit='return (validatebill("+id+"));'>" +
                  "<div class='form-group'>"+
                    "<label class='col-md-3 control-label'>Select Amount Percentage</label>"+
                    "<div class='col-md-3'>"+
                      "<select id='percentage"+id+"'>"+
                        "<option value='0' >Select Percentage</option>"+
                        "<option value='20' >20 Percent</option>"+
                        "<option value='80' >80 Percent</option>"+
                      "</select>"+
                    "</div>"+
                    "<label class='col-md-3 control-label'>Select CEM Discount</label>"+
                    "<div class='col-md-3'>"+
                      "<select id='discount"+id+"'>"+
                        "<option value='0' selected>No Discount</option>"+
                        "<option value='5' >5 Percent</option>"+
                        "<option value='10' >10 Percent</option>"+
                      "</select>"+
                    "</div>"+
                  "</div>"+
                  "<div class='form-group'>"+
                    "<label class='col-md-3 control-label'></label>"+
                    "<div class='col-md-7'>"+
                      "<button type='submit' class='btn btn-success pull-right' >Submit Details</button>"+
                    "</div>"+
                  "</div>"+
                "</form>";
  $("#workerform_"+id).show().html(meeting);
}

function completeDetails(id, userId) {
  $.ajax({
    type: "POST",
    url: "ajax/Requestdetails.php",
    data: "sr_id="+id,
    cache: false,
    success: function(result){
      $("#userDetails_"+userId).show().html(result);
    }
  });
  return false;
}

function viewDetails(id, type) {
  $.ajax({
    type: "POST",
    url: "ajax/userDetails.php",
    data: "user_id="+ id + "&type=" + type,
    cache: false,
    success: function(result){
      //alert(result);
      $("#userDetails_"+id).show().html(result); 
    }
  });
  return false;
}

function removearea(id) {
  $('#'+id).remove();
}

function removeskill(id) {
  $('#'+id).remove();
}

function viewrequests(status, type, userId) {
  $.ajax({
    type: "POST",
    url: "ajax/employeeRequests.php",
    data: "status="+status +"&type="+type+"&user_id="+userId,
    cache: false,
    success: function(result){
      $("#userDetails_"+userId).show().html(result);
      $('#example1').DataTable();
    }
  });
  return false;
}

function viewNotes (Id, type) {
  $.ajax({
    type: "POST",
    url: "ajax/notesDetails.php",
    data: "sr_id="+ Id + "&type=" + type,
    cache: false,
    success: function(result){
      //alert(result);
      $("#workerform_"+Id).show().html(result); 
    },
    error: function(result){
      alert("Error Occured");
      return false;
    }
  });
}

function Update (id) {
  location="update.php?sr_id="+id;
}

function validateSearch() {
  var search = $('#searchworker').val();
  if(validatePhone(search) == false){
    alert('Enter valid number');
    return false;
  }
  else {
    $.ajax({
      type: "POST",
      url: "ajax/search.php",
      data: "phone="+search,
      cache: false,
      success: function(result){
        $('#searchworker').val("");
        $(".searchresult").html('');
        $(".searchresult").append(result); 
      },
      error: function(result){
        alert("Error Occured");
        return false;
      }
    });
    return false;
  }
}

function requestData(status) {
  $.ajax({
    type: "POST",
    url: "ajax/requestData.php",
    data: "status="+ status ,
    cache: false,
    success: function(result){
      $('.middlePanel').html('');
      $('.middlePanel').append(result);
    },
    error: function(result){
      alert("Error Occured");
      return false;
    }
  });
}

function getstatics() {
  $.get('../components/static.php', function(data) {
    $('.middlePanel').html('');
    $('.middlePanel').append(data);
  });
}

function getallprintArea() {
  $.get('../components/printArea.php', function(data) {
    $('.middlePanel').html('');
    $('.middlePanel').append(data);
  });
}

function insertnewworkerform() {
  $.get('../components/workerform.php', function(data) {
    $('.middlePanel').html('');
    $('.middlePanel').append(data);
    $('#birth_date').datepicker();
    $('#timing').timepicker();
    $('#timing2').timepicker();
  });
}

function insertnewrequestform() {
  $.get('../components/requestform.php', function(data) {
    $('.middlePanel').html('');
    $('.middlePanel').append(data);
    $('#created_time').datepicker();
    $('#timing').timepicker();
    $('#timing2').timepicker();
  });
}

function insertnewuserform() {
  $.get('../components/userform.php', function(data) {
    $('.middlePanel').html('');
    $('.middlePanel').append(data);
    $('#created_time').datepicker();
    $('#timing').timepicker();
    $('#timing2').timepicker();
  });
}

function getgraphs(type){
  $.get('../components/business_inc.php?status='+type, function(data) {
    $('.middlePanel').html('');
    $('.middlePanel').append(data);
    if(type = "users") $('#example1').DataTable();
    //drawChart();
  });
}

function getRequestData(status){
  switch(status){
    case 'addUser':
      insertnewuserform();
      break; 

    case 'addRequest':
      insertnewrequestform();
      break;

    case 'users':
      getgraphs('users');
      break;

    case 'finencial':
      getgraphs('finencial');
      break;

    case 'collectionRate':
      getgraphs('collectionRate');
      break;

    case 'typeRequest':
      getgraphs('typeRequest');
      break;

    case 'monthlyandondemand':
      getgraphs('monthlyandondemand');
      break;

    case 'maingraph':
      getgraphs('maingraph');
      break;

    case 'addWorker':
      insertnewworkerform();
      break;  

    case 'printArea':
      getallprintArea();
      break;  

    case 'statics':
      getstatics();
      break; 

    default :
      requestData(status);
      break;
  }
}

function getDefaultData(employee_type) {
  switch(employee_type){
    case 'me':
      requestData("0");
      break; 

    case 'cem':
      requestData("0");
      break;  

    case 'admin':
      requestData("all");
      break;  

    case 'cem_manager':
      getstatics();
      break; 

    case 'accountant':
      insertnewuserform();
      break;

    case 'ba':
      getgraphs('maingraph');
      break;

    case 'dev':
      insertnewuserform();
      break;

    case 'operator':
      insertnewuserform();
      break; 

    default :
      insertnewuserform();
      break;
  }
}

function reportcard(type, id) {
  $.ajax({
    type: "POST",
    url: "ajax/reportcard.php",
    data: "type="+type+"&id="+id,
    cache: false,
    success: function(result){
      $("#reportcard_"+id).show().html(result);
    }
  });
}

function toggleform() {
  $(".searchform").toggle();
}

function addworker(request_id, id){
  var hourdata = "";
  hourdata += "<option value='0' >Select hours</option>";
  for (var i = 2; i < 25; i++) {
    hourdata += "<option value="+i+">"+i+"</option>";
  }
  if (id == 1){
    var worker_modal = "<form class='form-horizontal' id='worker_details_form"+request_id+"' onsubmit='return (validateWorkerDetails("+request_id+","+ id+"));'>" +
                        "<div class='form-group'>"+
                          "<label class='col-md-3 control-label'>First Name</label>"+
                          "<div class='col-md-3'>"+
                            "<input type='text' id ='first_name"+request_id+"' onkeyup='nospaces(this);' class='form-control' placeholder='First Name' />"+
                          "</div>"+
                          "<label class='col-md-3 control-label'>Last Name</label>"+
                          "<div class='col-md-3'>"+
                            "<input type='text' id ='last_name"+request_id+"' onkeyup='nospaces(this);' class='form-control' placeholder='Last Name' />"+
                          "</div>"+
                        "</div>"+
                        "<div class='form-group'>"+
                          "<label class='col-md-3 control-label'>Mobile No.</label>"+
                          "<div class='col-md-3'>"+
                            "<input type='text' id='mobile"+request_id+"' class='form-control' onkeyup='nospaces(this);' placeholder='Enter 10 digit mobile number'>"+
                          "</div>"+
                          "<label class='col-md-3 control-label'>Emergancy Mobile No.</label>"+
                          "<div class='col-md-3'>"+
                            "<input type='text' id='emergancy_mobile"+request_id+"' onkeyup='nospaces(this);' class='form-control' placeholder='Enter 10 digit mobile number'>"+
                          "</div>"+
                        "</div>"+
                        "<div class='form-group'>"+
                          "<label class='col-md-2 control-label'>Expected Salary</label>"+
                          "<div class='col-md-6 input-group'>"+
                            "<input type='number' id ='salary"+request_id+"' class='form-control' placeholder='Enter Salary' />"+
                            "<div class='input-group-addon'>To</div>"+
                            "<input type='number' id ='salary2"+request_id+"' class='form-control' placeholder='Enter Salary' />"+
                          "</div>"+
                        "</div>"+
                        "<div class='form-group'>"+
                          "<label class='col-md-3 control-label'>Age</label>"+
                          "<div class='col-md-3'>"+
                            "<input type='number' id='age"+request_id+"' class='form-control' placeholder='Age in years'>"+
                          "</div>"+
                        "</div>"+
                        "<div class='form-group'>"+
                          "<label class='col-md-3 control-label'>Current address</label>"+
                          "<div class='col-md-3'>"+
                            "<textarea type='text' id='current_address"+request_id+"' class='form-control' placeholder='Full Address' rows='4'></textarea>"+
                          "</div>"+
                          "<label class='col-md-3 control-label'>Parmanent address</label>"+
                          "<div class='col-md-3'>"+
                            "<textarea type='text' id='parmanent_address"+request_id+"' class='form-control' placeholder='Full Address' rows='4'></textarea>"+
                          "</div>"+
                        "</div>"+
                        "<div class='form-group'>"+
                          "<label class='col-md-3 control-label'>Highest Education</label>"+
                          "<div class='col-md-3'>"+
                            "<input type='text' id='education"+request_id+"' class='form-control' onkeyup='nospaces(this);' placeholder='Highest Education'>"+
                          "</div>"+
                          "<label for='demo-msk-date' class='col-md-3 control-label'>Experience</label>"+
                          "<div class='col-md-3'>"+
                            "<input type='number' id='experience"+request_id+"' class='form-control' placeholder='Experience in Years'>"+
                          "</div>"+
                        "</div>"+
                        "<div class='form-group'>"+
                          "<label class='col-lg-3 control-label'>Gender</label>"+
                          "<div class='col-lg-3'>"+
                            "<select class='selectpicker' id='gender"+request_id+"' data-live-search='true' data-width='100%'>" +   
                              "<option value='Male'>Male </option>"+
                              "<option value='Female'>Female</option>"+
                              "<option value='Other'>Other</option>"+
                            "</select>"+
                          "</div>"+
                          "<label for='demo-msk-date' class='col-md-3 control-label'>Date of Birth</label>"+
                          "<div class='col-md-3'>"+
                            "<input type='text' id='birth_date"+request_id+"' class='form-control' placeholder='dd/mm/yyyy'>"+
                          "</div>"+
                        "</div>"+
                        "<div class='form-group'>"+
                          "<label class='col-md-2 control-label'>Timings</label>"+
                          "<div class='col-md-6 input-group'>"+
                            "<input type='text' id ='timing"+request_id+"' class='form-control' placeholder='Enter Time' />"+
                            "<div class='input-group-addon'>To</div>"+
                            "<input type='text' id ='timing2"+request_id+"' class='form-control' placeholder='Enter Time' />"+
                          "</div>"+
                        "</div>"+
                        "<div class='form-group'>"+
                          "<label for='demo-msk-date' class='col-md-3 control-label'>Working Hours</label>"+
                          "<div class='col-md-3'>"+
                            "<select id='work_time"+request_id+"'>"+
                            "</select>"+
                          "</div>"+
                        "</div>"+
                        "<div class='form-group'>"+
                          "<label class='col-md-3 control-label'>Remarks</label>"+
                          "<div class='col-md-3'>"+
                            "<textarea type='text' id='remarks"+request_id+"' class='form-control' placeholder='Remarks' rows='4'></textarea>"+
                          "</div>"+
                          "<label class='col-md-3 control-label'>Police Verification</label>"+
                          "<div class='col-md-3'>"+
                            "<select class='selectpicker' id='police"+request_id+"' name='police' data-live-search='true' data-width='100%'>" +   
                              "<option value='yes'>yes </option>"+
                              "<option value='no'>no</option>"+
                            "</select>"+
                          "</div>"+
                        "</div>"+
                        "<div class='form-group'>"+
                          "<label class='col-md-3 control-label'>Languages</label>"+
                          "<div class='col-md-3'>"+
                            "<input type='text' id='languages"+request_id+"' class='form-control' onkeyup='nospaces(this);' placeholder='Enter atleast one language' data-role='tagsinput'>" +
                            "<small class='help'>Enter multimple seperated by , </small>"+
                          "</div>"+
                          "<label class='col-md-3 control-label'>Services</label>"+
                          "<div class='col-md-3'>"+       
                            "<input type='text' id='services"+request_id+"'  class='form-control' onkeyup='nospaces(this);' placeholder='Enter atleast one Service' data-role='tagsinput'>"+
                            "<small class='help'>Enter multimple seperated by , </small>"+
                          "</div>"+
                        "</div>"+ 
                        "<div class='form-group'>"+
                          "<label class='col-md-3 control-label'>Enter New Skill</label>"+
                          "<div class='col-md-3'>"+
                            "<input type='text' id='newskill"+request_id+"' onkeyup='nospaces(this);' class='form-control' placeholder='Enter Skill' data-role='tagsinput'>"+
                          "</div>"+
                          "<label class='col-md-2 control-label'>or select Skills</label>"+
                          "<div class='col-md-2'>"+
                            "<select class='selectpicker"+request_id+"' id='skills"+request_id+"' onchange='getselectedskill("+request_id+", 1);' data-live-search='true' data-width='100%' >"+ 
                            "</select>"+
                            "<div id='selectedskills"+request_id+"'></div>"+
                          "</div>"+
                        "</div>"+
                        "<div class='form-group'>"+
                          "<label class='col-md-3 control-label'>Enter New Worker Area </label>"+
                          "<div class='col-md-3'>"+
                            "<input type='text' id='worker_area"+request_id+"' class='form-control' placeholder='Enter Worker area' data-role='tagsinput'>"+
                          "</div>"+
                          "<label class='col-md-2 control-label'>or select Worker Area</label>"+
                          "<div class='col-md-2'>"+
                            "<select class='selectpick"+request_id+"' id='workerareas"+request_id+"' onchange='getselectedarea("+request_id+", 1);' data-live-search='true' data-width='100%' >"+ 
                            "</select>"+
                            "<div id='selectedworkerareas"+request_id+"'></div>"+
                          "</div>"+
                        "</div>"+
                        "<div class='form-group'>"+
                          "<label class='col-md-3 control-label'></label>"+
                          "<div class='col-md-7'>"+
                            "<button type='submit' class='btn btn-success pull-right' >Submit Details</button>"+
                          "</div>"+
                        "</div>"+
                      "</form>";
    $("#workerform_"+request_id).show().html(worker_modal);
    $('#work_time'+request_id).append(hourdata);
    $('#birth_date'+request_id).datepicker();
    $('#timing'+request_id).timepicker();
    $('#timing2'+request_id).timepicker(); 
    //document.getElementById("addworker").innerHTML = worker_modal;
    //$("ddworker").innerhtml(worker_modal) }
  }
  else {
    var worker_modal = "<form class='form-horizontal' id='worker_details_form"+request_id+"' onsubmit='return (validateWorkerDetails("+request_id+","+ id+"));'>" +
                        "<div class='form-group'>"+
                          "<label class='col-md-3 control-label'>First Name</label>"+
                          "<div class='col-md-3'>"+
                            "<input type='text' id ='2first_name"+request_id+"' onkeyup='nospaces(this);' class='form-control' placeholder='First Name' />"+
                          "</div>"+
                          "<label class='col-md-3 control-label'>Last Name</label>"+
                          "<div class='col-md-3'>"+
                            "<input type='text' id ='2last_name"+request_id+"' onkeyup='nospaces(this);' class='form-control' placeholder='Last Name' />"+
                          "</div>"+
                        "</div>"+
                        "<div class='form-group'>"+
                          "<label class='col-md-3 control-label'>Mobile No.</label>"+
                          "<div class='col-md-3'>"+
                            "<input type='text' id='2mobile"+request_id+"' onkeyup='nospaces(this);' class='form-control' placeholder='Enter 10 digit mobile number'>"+
                          "</div>"+
                          "<label class='col-md-3 control-label'>Emergancy Mobile No.</label>"+
                          "<div class='col-md-3'>"+
                            "<input type='text' id='2emergancy_mobile"+request_id+"' onkeyup='nospaces(this);' class='form-control' placeholder='Enter 10 digit mobile number'>"+
                          "</div>"+
                        "</div>"+
                        "<div class='form-group'>"+
                          "<label class='col-md-2 control-label'>Expected Salary</label>"+
                          "<div class='col-md-6 input-group'>"+
                            "<input type='number' id ='2salary"+request_id+"' class='form-control' placeholder='Enter Salary' />"+
                            "<div class='input-group-addon'>To</div>"+
                            "<input type='number' id ='2salary2"+request_id+"' class='form-control' placeholder='Enter Salary' />"+
                          "</div>"+
                        "</div>"+
                        "<div class='form-group'>"+
                          "<label class='col-md-3 control-label'>Age</label>"+
                          "<div class='col-md-3'>"+
                            "<input type='number' id='2age"+request_id+"' class='form-control' placeholder='Age in years'>"+
                          "</div>"+
                        "</div>"+
                        "<div class='form-group'>"+
                          "<label class='col-md-3 control-label'>Current address</label>"+
                          "<div class='col-md-3'>"+
                            "<textarea type='text' id='2current_address"+request_id+"' class='form-control' placeholder='Full Address' rows='4'></textarea>"+
                          "</div>"+
                          "<label class='col-md-3 control-label'>Parmanent address</label>"+
                          "<div class='col-md-3'>"+
                            "<textarea type='text' id='2parmanent_address"+request_id+"' class='form-control' placeholder='Full Address' rows='4'></textarea>"+
                          "</div>"+
                        "</div>"+
                        "<div class='form-group'>"+
                          "<label class='col-md-3 control-label'>Highest Education</label>"+
                          "<div class='col-md-3'>"+
                            "<input type='text' id='2education"+request_id+"' class='form-control' onkeyup='nospaces(this);' placeholder='Highest Education'>"+
                          "</div>"+
                          "<label for='demo-msk-date' class='col-md-3 control-label'>Experience</label>"+
                          "<div class='col-md-3'>"+
                            "<input type='number' id='2experience"+request_id+"' class='form-control' placeholder='Experience in Years'>"+
                          "</div>"+
                        "</div>"+
                        "<div class='form-group'>"+
                          "<label class='col-lg-3 control-label'>Gender</label>"+
                          "<div class='col-lg-3'>"+
                            "<select class='selectpicker' id='2gender"+request_id+"' data-live-search='true' data-width='100%'>" +   
                              "<option value='Male'>Male </option>"+
                              "<option value='Female'>Female</option>"+
                              "<option value='Other'>Other</option>"+
                            "</select>"+
                          "</div>"+
                          "<label for='demo-msk-date' class='col-md-3 control-label'>Date of Birth</label>"+
                          "<div class='col-md-3'>"+
                            "<input type='text' id='2birth_date"+request_id+"' class='form-control' placeholder='dd/mm/yyyy'>"+
                          "</div>"+
                        "</div>"+
                        "<div class='form-group'>"+
                          "<label class='col-md-2 control-label'>Timings</label>"+
                            "<div class='col-md-6 input-group'>"+
                            "<input type='text' id ='2timing"+request_id+"' class='form-control' placeholder='Enter Time' />"+
                            "<div class='input-group-addon'>To</div>"+
                            "<input type='text' id ='2timing2"+request_id+"' class='form-control' placeholder='Enter Time' />"+
                          "</div>"+
                        "</div>"+
                        "<div class='form-group'>"+
                          "<label for='demo-msk-date' class='col-md-3 control-label'>Working Hours</label>"+
                          "<div class='col-md-3'>"+
                            "<select id='2work_time"+request_id+"'>"+
                            "</select>"+
                          "</div>"+
                        "</div>"+
                        "<div class='form-group'>"+
                          "<label class='col-md-3 control-label'>Remarks</label>"+
                          "<div class='col-md-3'>"+
                            "<textarea type='text' id='2remarks"+request_id+"' class='form-control' placeholder='Remarks' rows='4'></textarea>"+
                          "</div>"+
                          "<label class='col-md-3 control-label'>Police Verification</label>"+
                          "<div class='col-md-3'>"+
                            "<select class='selectpicker' id='2police"+request_id+"' name='police' data-live-search='true' data-width='100%'>" +   
                              "<option value='yes'>yes </option>"+
                              "<option value='no'>no</option>"+
                            "</select>"+
                          "</div>"+
                        "</div>"+
                        "<div class='form-group'>"+
                          "<label class='col-md-3 control-label'>Languages</label>"+
                          "<div class='col-md-3'>"+
                            "<input type='text' id='2languages"+request_id+"' class='form-control' onkeyup='nospaces(this);' placeholder='Enter atleast one language' data-role='tagsinput'>" +
                            "<small class='help'>Enter multimple seperated by , or Enter</small>"+
                          "</div>"+
                          "<label class='col-md-3 control-label'>Services</label>"+
                          "<div class='col-md-3'>"+       
                            "<input type='text' id='2services"+request_id+"'  class='form-control' onkeyup='nospaces(this);' placeholder='Enter atleast one Service' data-role='tagsinput'>"+
                            "<small class='help'>Enter multimple seperated by , </small>"+
                          "</div>"+
                        "</div>"+ 
                        "<div class='form-group'>"+
                          "<label class='col-md-3 control-label'>Enter New Skill</label>"+
                          "<div class='col-md-3'>"+
                            "<input type='text' id='2newskill"+request_id+"' onkeyup='nospaces(this);' class='form-control' placeholder='Enter Skill' data-role='tagsinput'>"+
                          "</div>"+
                          "<label class='col-md-2 control-label'>or select Skills</label>"+
                          "<div class='col-md-2'>"+
                            "<select class='selectpicker"+request_id+"' id='2skills"+request_id+"' onchange='getselectedskill("+request_id+", 2);' data-live-search='true' data-width='100%' >"+ 
                            "</select>"+
                            "<div id='2selectedskills"+request_id+"'></div>"+
                          "</div>"+
                        "</div>"+
                        "<div class='form-group'>"+
                          "<label class='col-md-3 control-label'>Enter New Worker Area </label>"+
                          "<div class='col-md-3'>"+
                            "<input type='text' id='2worker_area"+request_id+"' class='form-control' placeholder='Enter Worker area' data-role='tagsinput'>"+
                          "</div>"+
                          "<label class='col-md-2 control-label'>or select Worker Area</label>"+
                          "<div class='col-md-2'>"+
                            "<select class='selectpick"+request_id+"' id='2workerareas"+request_id+"' onchange='getselectedarea("+request_id+", 2);' data-live-search='true' data-width='100%' >"+ 
                            "</select>"+
                            "<div id='2selectedworkerareas"+request_id+"'></div>"+
                          "</div>"+
                        "</div>"+
                        "<div class='form-group'>"+
                          "<label class='col-md-3 control-label'></label>"+
                          "<div class='col-md-7'>"+
                            "<button type='submit' class='btn btn-success pull-right'>Submit Details</button>"+
                          "</div>"+
                        "</div>"+
                      "</form>";
    $("#workerform_"+request_id).show().html(worker_modal);
    $('#2work_time'+request_id).append(hourdata); 
    $('#2birth_date'+request_id).datepicker();
    $('#2timing'+request_id).timepicker();
    $('#2timing2'+request_id).timepicker();  
  }
  $.ajax({
    type: "POST",
    url: "ajax/getskill.php",
    data: "type="+id ,
    cache: false,
    success: function(result){
      $('.selectpicker'+request_id).append(result);
    }
  });
  $.ajax({
    type: "POST",
    url: "ajax/getareas.php",
    data: "type="+id ,
    cache: false,
    success: function(result){
      $('.selectpick'+request_id).append(result);
    }
  });                  
}
                      /*"<div class='form-group'>"+
                        "<label class='col-md-3 control-label'>Address Proof Name</label>"+
                        "<div class='col-md-3'>"+
                          "<select class='selectpicker' id='address_proof_name"+request_id+"' data-live-search='true' data-width='100%'>"+ 
                            "<option value='Voter Id' >Voter Id </option>"+
                            "<option value='Adhaar Card' >Adhaar Card</option>"+
                            "<option value='Driving License' >Driving License</option>"+
                            "<option value='Education Certificate' >Education Certificate</option>"+
                            "<option value='Bank Account' >Bank Account</option>"+
                            "<option value='Passport' >Passport</option>"+
                          "</select>"+
                        "</div>"+
                        "<label class='col-md-3 control-label'>Address Proof No</label>"+
                        "<div class='col-md-3'>"+
                          "<input type='text' id ='address_proof_id"+request_id+"' class='form-control' placeholder='Address Proof Id' />"+
                        "</div>"+
                      "</div>"+
                      "<div class='form-group'>"+
                        "<label class='col-md-3 control-label'>Id Proof Name</label>"+
                        "<div class='col-md-3'>"+
                          "<select class='selectpicker' id='id_proof_name"+request_id+"' data-live-search='true' data-width='100%'>"+    
                            "<option value='Voter Id' >Voter Id </option>"+
                            "<option value='Adhaar Card' >Adhaar Card</option>"+
                            "<option value='Driving License' >Driving License</option>"+
                            "<option value='Education Certificate' >Education Certificate</option>"+
                            "<option value='Bank Account' >Bank Account</option>"+
                            "<option value='Passport' >Passport</option>"+
                          "</select>"+
                        "</div>"+
                        "<label class='col-md-3 control-label'>Id Proof No</label>"+
                        "<div class='col-md-3'>"+
                          "<input type='text' id ='id_proof_id"+request_id+"' class='form-control' placeholder='Id Proof Id' />"+
                        "</div>"+
                      "</div>"+*/

                      /*"<div class='form-group'>"+
                        "<label class='col-md-3 control-label'>Address Proof Name</label>"+
                        "<div class='col-md-3'>"+
                          "<select  id='2address_proof_name"+request_id+"' >"+ 
                            "<option value='Voter Id' >Voter Id </option>"+
                            "<option value='Adhaar Card' >Adhaar Card</option>"+
                            "<option value='Driving License' >Driving License</option>"+
                            "<option value='Education Certificate' >Education Certificate</option>"+
                            "<option value='Bank Account' >Bank Account</option>"+
                            "<option value='Passport' >Passport</option>"+
                          "</select>"+
                        "</div>"+
                        "<label class='col-md-3 control-label'>Address Proof No</label>"+
                        "<div class='col-md-3'>"+
                          "<input type='text' id ='2address_proof_id"+request_id+"' class='form-control' placeholder='Address Proof Id' />"+
                        "</div>"+
                      "</div>"+
                      "<div class='form-group'>"+
                        "<label class='col-md-3 control-label'>Id Proof Name</label>"+
                        "<div class='col-md-3'>"+
                          "<select  id='2id_proof_name"+request_id+"' >"+    
                            "<option value='Voter Id' >Voter Id </option>"+
                            "<option value='Adhaar Card' >Adhaar Card</option>"+
                            "<option value='Driving License' >Driving License</option>"+
                            "<option value='Education Certificate' >Education Certificate</option>"+
                            "<option value='Bank Account' >Bank Account</option>"+
                            "<option value='Passport' >Passport</option>"+
                          "</select>"+
                        "</div>"+
                        "<label class='col-md-3 control-label'>Id Proof No</label>"+
                        "<div class='col-md-3'>"+
                          "<input type='text' id ='2id_proof_id"+request_id+"' class='form-control' placeholder='Id Proof Id' />"+
                        "</div>"+
                      "</div>"+*/