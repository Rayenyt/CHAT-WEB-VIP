document.addEventListener("DOMContentLoaded", function(){
    const form = document.getElementById("chat-form");
    const input = document.getElementById("chat-input");
    const box = document.getElementById("chat-box");

    form.addEventListener("submit", function(e){
        e.preventDefault();
        const msg = input.value.trim();
        if(msg==='') return;

        const div = document.createElement("div");
        div.className = "message self";
        div.textContent = msg;
        box.appendChild(div);
        box.scrollTop = box.scrollHeight;

        fetch("bot.php",{
            method:"POST",
            headers:{"Content-Type":"application/x-www-form-urlencoded"},
            body:"message="+encodeURIComponent(msg)+"&user_id="+userId
        });

        input.value="";
    });

    setInterval(()=>{
        fetch("bot.php?get=1&user_id="+userId)
        .then(r=>r.json())
        .then(data=>{
            box.innerHTML="";
            data.forEach(m=>{
                const div = document.createElement("div");
                div.className = "message "+(m.self?'self':'other');
                div.textContent = m.text;
                box.appendChild(div);
            });
            box.scrollTop = box.scrollHeight;
        });
    },2000);
});
