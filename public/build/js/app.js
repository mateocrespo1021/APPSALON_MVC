let paso=1,pasoInicial=1,pasoFinal=3;const cita={id:"",nombre:"",fecha:"",hora:"",servicios:[]};function iniciarApp(){mostrarSeccion(),tabs(),botonesPaginador(),paginaSiguiente(),paginaAnterior(),consultarAPI(),nombreCliente(),seleccionarFecha(),seleccionarHora(),mostrarResumen(),idCliente()}function mostrarSeccion(){const e=document.querySelector(".mostrar"),t=document.querySelector(".actual");e&&e.classList.remove("mostrar"),t&&t.classList.remove("actual");const a="#paso-"+paso;document.querySelector(a).classList.add("mostrar");document.querySelector(`[data-paso="${paso}"]`).classList.add("actual")}function tabs(){document.querySelectorAll(".tabs button").forEach(e=>{e.addEventListener("click",e=>{paso=parseInt(e.target.dataset.paso),mostrarSeccion(),botonesPaginador()})})}function botonesPaginador(){mostrarSeccion();const e=document.querySelector("#anterior"),t=document.querySelector("#siguiente");1===paso?(e.classList.add("ocultar"),t.classList.remove("ocultar")):3===paso?(e.classList.remove("ocultar"),t.classList.add("ocultar"),mostrarResumen()):(e.classList.remove("ocultar"),t.classList.remove("ocultar"))}function paginaAnterior(){document.querySelector("#anterior").addEventListener("click",()=>{paso<=pasoInicial||(paso--,botonesPaginador())})}function paginaSiguiente(){document.querySelector("#siguiente").addEventListener("click",()=>{paso>=pasoFinal||(paso++,botonesPaginador())})}async function consultarAPI(){try{const e="http://localhost:3000/api/servicios",t=await fetch(e);mostrarServicios(await t.json())}catch(e){console.log(e)}}function mostrarServicios(e){e.forEach(e=>{const{id:t,nombre:a,precio:o}=e,n=document.createElement("p");n.classList.add("nombre-servicio"),n.textContent=a;const r=document.createElement("p");r.classList.add("precio-servicio"),r.textContent="$ "+o;const c=document.createElement("div");c.classList.add("servicio"),c.dataset.idServicio=t,c.onclick=()=>seleccionarServicio(e),c.appendChild(n),c.appendChild(r),document.querySelector("#servicios").appendChild(c)})}function seleccionarServicio(e){const{id:t}=e,{servicios:a}=cita;if(a.some(e=>e.id===t)){serviciosActual=a.filter(e=>e.id!==t),cita.servicios=[...serviciosActual];document.querySelector(`[data-id-servicio="${t}"]`).classList.remove("seleccionado")}else{cita.servicios=[...a,e];document.querySelector(`[data-id-servicio="${t}"]`).classList.add("seleccionado")}}function idCliente(){cita.id=document.querySelector("#id").value}function nombreCliente(){cita.nombre=document.querySelector("#nombre").value}function seleccionarFecha(){document.querySelector("#fecha").addEventListener("input",e=>{const t=new Date(e.target.value).getUTCDay();if([6,0].includes(t))return e.target.value="",void mostrarAlerta("Fines de semana no permitidos","error",".formulario");cita.fecha=e.target.value})}function seleccionarHora(){document.querySelector("#hora").addEventListener("input",e=>{const t=e.target.value,a=t.split(":")[0];if(a<10||a>18)return e.target.value="",void mostrarAlerta("Hora No Valida","error",".formulario");cita.hora=t})}function mostrarAlerta(e,t,a,o=!0){document.querySelector(".alerta")&&document.querySelector(".alerta").remove();const n=document.createElement("div");n.textContent=e,n.classList.add("alerta"),n.classList.add(t);document.querySelector(a).appendChild(n),o&&setTimeout(()=>{n.remove()},3e3)}function mostrarResumen(){const e=document.querySelector(".contenido-resumen");for(;e.firstChild;)e.removeChild(e.firstChild);if(Object.values(cita).includes("")||0===cita.servicios.length)return void mostrarAlerta("Falta datos de Servicios , Fecha u Hora","error",".contenido-resumen",!1);const{nombre:t,fecha:a,hora:o,servicios:n}=cita,r=document.createElement("h3");r.textContent="Resumen de Servicios",e.appendChild(r),n.forEach(t=>{const{id:a,precio:o,nombre:n}=t,r=document.createElement("div");r.classList.add("contenedor-servicio");const c=document.createElement("p");c.textContent=n;const i=document.createElement("p");i.innerHTML="<span>Precio:</span>$ "+o,r.appendChild(c),r.appendChild(i),e.appendChild(r)});const c=document.createElement("h3");c.textContent="Resumen de Cita",e.appendChild(c);const i=document.createElement("p");i.innerHTML="<span>Nombre:</span> "+t;const s=new Date(a),d=s.getMonth(),l=s.getDate(),u=s.getFullYear(),m=new Date(Date.UTC(u,d,l)).toLocaleDateString("es-MX",{weekday:"long",year:"numeric",month:"long",day:"numeric"}),p=document.createElement("p");p.innerHTML="<span>Fecha:</span> "+(m.charAt(0).toUpperCase()+m.slice(1));const v=document.createElement("p");v.innerHTML="<span>Hora:</span> "+o;const h=document.createElement("button");h.classList.add("boton"),h.textContent="Reservar Cita",h.onclick=reservarCita,e.appendChild(i),e.appendChild(p),e.appendChild(v),e.appendChild(h)}async function reservarCita(){const{id:e,fecha:t,hora:a,servicios:o}=cita,n=o.map(e=>parseInt(e.id)),r=new FormData;r.append("usuarioId",e),r.append("fecha",t),r.append("hora",a),r.append("servicios",n);try{const e="http://localhost:3000/api/citas",t=await fetch(e,{method:"POST",body:r});(await t.json()).resultado&&Swal.fire({icon:"success",title:"Cita Creada...",text:"Tu cita fue creada correctamente",button:"OK"}).then(()=>{window.location.reload()})}catch(e){Swal.fire({icon:"error",title:"Error",text:"Hubo un error al guardar la cita"})}}document.addEventListener("DOMContentLoaded",()=>{iniciarApp()});