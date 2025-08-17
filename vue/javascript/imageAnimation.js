/* ======================================================
   CANVAS PARTICLES (lightweight)
   ====================================================== */
(() => {
  const canvas = document.getElementById('bg-canvas');
  const ctx = canvas.getContext('2d');
  let w = canvas.width = innerWidth;
  let h = canvas.height = innerHeight;
  const dpr = Math.min(window.devicePixelRatio || 1, 2);
  canvas.width = w * dpr; canvas.height = h * dpr; canvas.style.width = w + 'px'; canvas.style.height = h + 'px';
  ctx.scale(dpr, dpr);

  const particles = [];
  const NUM = Math.floor((w*h)/90000); // scalable count
  for(let i=0;i<NUM;i++){
    particles.push({
      x: Math.random()*w,
      y: Math.random()*h,
      r: 0.6 + Math.random()*1.6,
      vx: (Math.random()-0.5)*0.25,
      vy: (Math.random()-0.5)*0.25,
      life: Math.random()*100
    });
  }
  function draw(){
    ctx.clearRect(0,0,w,h);
    // subtle gradient glow center
    const g = ctx.createRadialGradient(w*0.15,h*0.1,0,w*0.5,h*0.5,Math.max(w,h));
    g.addColorStop(0,'rgba(5,240,122,0.03)');
    g.addColorStop(1,'rgba(0,0,0,0)');
    ctx.fillStyle = g; ctx.fillRect(0,0,w,h);

    for(const p of particles){
      p.x += p.vx; p.y += p.vy; p.life += 0.02;
      if(p.x < -10) p.x = w+10;
      if(p.x > w+10) p.x = -10;
      if(p.y < -10) p.y = h+10;
      if(p.y > h+10) p.y = -10;

      ctx.beginPath();
      const alpha = 0.12 + 0.3*Math.sin(p.life*0.05);
      ctx.fillStyle = `rgba(5,240,122,${alpha})`;
      ctx.arc(p.x, p.y, p.r, 0, Math.PI*2);
      ctx.fill();
    }

    requestAnimationFrame(draw);
  }
  // handle resize
  addEventListener('resize', ()=> {
    w = canvas.width = innerWidth;
    h = canvas.height = innerHeight;
    canvas.width = w * dpr; canvas.height = h * dpr; canvas.style.width = w + 'px'; canvas.style.height = h + 'px';
    ctx.scale(dpr,dpr);
  });
  draw();
})();

/* ======================================================
   SCROLL REVEAL (IntersectionObserver)
   ====================================================== */
(() => {
  const elems = document.querySelectorAll('.reveal');
  const io = new IntersectionObserver((entries)=>{
    entries.forEach(entry => {
      if(entry.isIntersecting){
        entry.target.classList.add('show');
        // once shown keep it
      }
    });
  }, {threshold: 0.12});
  elems.forEach(e => io.observe(e));
})();

/* ======================================================
   TILT EFFECT (mouse) for elements with data-tilt
   lightweight, not using any lib
   ====================================================== */
(() => {
  const tiltables = document.querySelectorAll('[data-tilt]');
  tiltables.forEach(el => {
    const rect = el.getBoundingClientRect();
    el.style.transformStyle = 'preserve-3d';
    function handle(e){
      const r = el.getBoundingClientRect();
      const px = (e.clientX - r.left)/r.width;
      const py = (e.clientY - r.top)/r.height;
      const rx = (py - 0.5) * 12; // rotateX
      const ry = (px - 0.5) * -12; // rotateY
      el.style.transform = `perspective(1000px) rotateX(${rx}deg) rotateY(${ry}deg) translateZ(4px)`;
    }
    function reset(){ el.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg)'; }
    el.addEventListener('mousemove', handle);
    el.addEventListener('mouseleave', reset);
    // touch fallback: small pop
    el.addEventListener('touchstart', ()=> el.style.transform = 'translateY(-6px) scale(1.02)');
    el.addEventListener('touchend', reset);
  });
})();

/* ======================================================
   Misc: year & accessibility (reduce motion)
   ====================================================== */
document.getElementById('year').textContent = new Date().getFullYear();
