function getCat(){
    let url =`http://yiiapiFull/category`;
    let promise = fetch(url)
        .then((resp) => {
            return resp.json()
        } )
        .then((data) =>{
            setCat(data)

        })
    function setCat(arrays){
        arrays = arrays['categorys'][0]
        let navbar = document.querySelector(".navbar-nav");
        navbar.innerHTML =``
        for(let j=0; j<arrays.length; j++){
            navbar.innerHTML +=`
            <li class="nav-item">
            <a class="nav-link" id="id_ctg_${arrays[j]["id_ctg"]}" href="#">${arrays[j]["name_ctg"]}</a>
          </li>
        `
        }

    }
}
getCat();
let navbar = document.querySelector(".navbar-nav");
navbar.onclick = function(e){
    let url =`http://yiiapiFull/search?tags=${e.target.innerHTML}`;
    let promise = fetch(url)
        .then((resp) => {
            return resp.json()
        } )
        .then((data) =>{
            getProductsTags(data)

        })
    function getProductsTags(arrays){
        let container = document.querySelector(".container");
        arrays = arrays["products"]
        container.innerHTML =`<div class="ListRooms"></div>`
        let ListRooms = document.querySelector(".ListRooms");
        for(let j=0; j<arrays.length; j++){
            ListRooms.innerHTML +=`
            <div class="section" id="${arrays[j]['id_prd']}"><img class="icon-img" src="${arrays[j]['image']}"><div class="text"><div class="name-room"><h3>${arrays[j]["name_prd"]}</h3></div><div class="prise-room"><div class="rub">${arrays[j]['price']}р<div class="down">сутки</div></div></div></div></div>
        `
        }
    }
}
let container = document.querySelector(".container");
container.onclick = function(e){
    if(e.target.classList[0]="icon-img" && e.target.id!=""){
        let url =`http://yiiapiFull/product/${e.target.id}`;

        let promise = fetch(url)
        .then((resp) => {
            return resp.json()
        } )
        .then((data) =>{
            getProductsTags(data)

        })
    function getProductsTags(arrays){
        container = document.querySelector(".container");
        let product = arrays["product"]
        let comment = arrays["comment"]
        if(product["id_status"]==0){
            product["id_status"] = `<div class="free" style="color:green">Свободен</div>`
        }
        else if(product["id_status"] = 1){
            product["id_status"] = `<div class="free">Занято</div>`
        }
        else if(product["id_status"] = 2){
            product["id_status"] = `<div class="free" style="color:#cfa700">В ремонте</div>`
        }
        else
            product["id_status"] = `<div class="free" style="color:#b100cf">В разработке</div>`
        container.innerHTML= `<div class="pageRoom">
        <div class="title">
          <div class="image-room"><img src="${product["image"]}"></div>
          <div class="title-text">
            <div class="title-name-room">
            ${product["name_prd"]}
            </div>
            <div class="title-description-room">
            ${product["description_prd"]}
            </div>
            <div class="title-prise-room">
            ${product["price"]}р
            </div>
          </div>
          ${product["id_status"]}
        </div>
    
        <div class="tags">${product["tags"]}</div>  
      </div>
      <div class="comments">
      </div>`
      if(comment["comments"]!=undefined){
        
        comment = comment["comments"]
        
        let comments = document.querySelector(".comments")
        comments.innerHTML=``;
        for(let i=0;i<comment.length;i++){
            comments.innerHTML +=`
            <div class="comment">
            <div class="title-comment">${comment[i]["FullName"]["name"]} ${comment[i]["FullName"]["surname"]} (${comment[i]["FullName"]["login"]})</div>
            <div class="text-comment">${comment[i]["text"]}</div>
            <div class="date-comment">${comment[i]["time"]}</div>
            </div>
            `;
        }
      }
      if(token){
        container.innerHTML+= `
            <div class="addComment">
            <textarea class="TextFotComment"></textarea>
            <button class="SubmitComment" onclick=addComments()>Отправить</button>
            </div>
        `
      }
    }
    }
    
}

function navbar_logout(){
    let url =`http://yiiapiFull/logout`;

        let promise = fetch(url, {
            method: "post",
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            },
            //make sure to serialize your JSON body
            headers: {
              Authorization: `Bearer ${token}`
            }
          })
        .then((resp) => {
            return resp.json()
        } )
        .then((data) =>{
            getProductsTags(data)

        })
        function getProductsTags(arrays){
            console.log(arrays);
        }
    let navbar_right = document.querySelector(".navbar-right");
    navbar_right.innerHTML =`
    <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
    <li class="loginBtn" onclick="loginBtn()"><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>`;
    token=false;
}

function regist(){
    let url =`http://yiiapiFull/registration`;

        let promise = fetch(url, {
            method: "post",
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            },
            //make sure to serialize your JSON body
            headers: {
              Authorization: `Bearer ${token}`
            }
          })
        .then((resp) => {
            return resp.json()
        } )
        .then((data) =>{
            getProductsTags(data)

        })
        function getProductsTags(arrays){
            console.log(arrays);
        }
    let navbar_right = document.querySelector(".navbar-right");
    navbar_right.innerHTML =`
    <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
    <li class="loginBtn" onclick="loginBtn()"><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>`;
    token=false;
}

function addComments(){
    let TextFotComment = document.querySelector(".TextFotComment");

    let user = {
        text: `${TextFotComment.value}`,
      };
      
    let url =`http://yiiapiFull/comment/1`;
    var params = new URLSearchParams(); 
    params.set('text', TextFotComment.value);
    let promise = fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json;charset=utf-8',
           'Authorization': `Bearer ${token}`
        },
        body: params
      })
      .then((resp) => {
        return resp.json()
    } )
    .then((data) =>{
        getProductsTags(data)

    })
    function getProductsTags(arrays){
        console.log(arrays);
    }


}
function loginBtn(){
container = document.querySelector(".container");
container.innerHTML=`
<form method="POST" action="http://yiiapiFull/auth">
  <!-- EmaLoginil input -->
  <div class="form-outline mb-4">
    <input type="text" id="form2Example1" class="form-control AuthLogin" name="login"/>
    <label class="form-label" for="form2Example1">Login</label>
  </div>

  <!-- Password input -->
  <div class="form-outline mb-4">
    <input type="password" id="form2Example2" class="form-control AuthPassword" name="password" />
    <label class="form-label" for="form2Example2">Password</label>
  </div>



  <!-- Submit button -->
  <input type="submit" class="btn btn-primary btn-block mb-4" value="Login"></input>

</form>
`

}