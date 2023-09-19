function course_learners(id){
    if($(`#mylearners_div`).length > 0 && $(`#mylearners_error`).length > 0){
        const div = $('#mylearners_div')[0];
        const errorText = $(`#mylearners_error`)[0];
        errorText.style.display = 'none';
        div.style.display = 'none';
        const xhr = new XMLHttpRequest();
        xhr.open('POST', './../blocks/mylearners/classes/inc/course.inc.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function(){
            if(this.status == 200){
                const text = JSON.parse(this.responseText);
                if(text['error']){
                    errorText.innerText = text['error'];
                    errorText.style.display = 'block';
                } else if(text['return']){
                    div.innerHTML = text['return'];
                    div.style.display = 'block';
                } else {
                    errorText.innerText = 'Loading error';
                    errorText.style.display = 'block';
                }
            } else  {
                errorText.innerText = 'Connection error';
                errorText.style.display = 'block';
            }
        }
        xhr.send(`id=${id}`);
    }
}
function submit_comp_dates(id, user, course){
    if($(`#ml_startdate_${id}`).length > 0 && $(`#ml_enddate_${id}`).length > 0 && $(`#ml_error_${id}`).length > 0){
        const errorText = $(`#ml_error_${id}`)[0];
        errorText.style.display = 'none';
        const xhr = new XMLHttpRequest();
        xhr.open('POST', './../blocks/mylearners/classes/inc/create_comp_date.inc.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function(){
            if(this.status == 200){
                const text = JSON.parse(this.responseText);
                if(text['error']){
                    errorText.innerText = text['error'];
                    errorText.style.display = 'block';
                } else if(text['return']){
                    get_target_colour(id, user, course);
                } else {
                    errorText.innerText = 'Submit error';
                    errorText.style.display = 'block';
                }
            } else {
                errorText.innerText = 'Connection error';
                errorText.style.display = 'block';
            }
        }
        xhr.send(`s=${$(`#ml_startdate_${id}`)[0].value}&e=${$(`#ml_enddate_${id}`)[0].value}&u=${user}&c=${course}`);
    }
}
function get_target_colour(id, user, course){
    if($(`#on_target_${id}`).length > 0){
        const td = $(`#on_target_${id}`)[0];
        td.innerHTML = '';
        const xhr = new XMLHttpRequest();
        xhr.open('POST', './../blocks/mylearners/classes/inc/get_target_colour.inc.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function(){
            if(this.status == 200){
                const text = JSON.parse(this.responseText);
                if(text['error']){
                    td.innerHTML = "<h2 class='text-center text-danger'>"+text['error']+"</h2>";
                } else if(text['return']){
                    td.setAttribute('style', 'background-color:'+text['return']+';');
                } else {
                    td.innerHTML = "<h2 class='text-center text-danger'>Loading error</h2>";
                }
            } else {
                td.innerHTML = "<h2 class='text-center text-danger'>Connection error</h2>";
            }
        }
        xhr.send(`u=${user}&c=${course}`);
    }
}
function mylearners_help(){
    if($(`#mylearners_help`).length > 0){
        const help = $(`#mylearners_help`)[0];
        if(help.style.display === 'none'){
            help.style.display = 'block';
        } else if(help.style.display === 'block'){
            help.style.display = 'none';
        }
    }
}