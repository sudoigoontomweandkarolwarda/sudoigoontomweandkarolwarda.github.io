<?php
// Define data storage file
define('DB_FILE', 'database.json');

// Handle incoming API requests from the frontend
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    // Initialize empty structure if file doesn't exist
    if (!file_exists(DB_FILE)) {
        file_put_contents(DB_FILE, json_encode(['channels' => []]));
    }
    
    if ($_GET['action'] === 'load') {
        echo file_get_contents(DB_FILE);
        exit;
    }
    
    if ($_GET['action'] === 'save') {
        $input = file_get_contents('php://input');
        if (json_decode($input) !== null) {
            file_put_contents(DB_FILE, $input);
            echo json_encode(['status' => 'success']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
        }
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>⚡ ELITA // IPTV PREMIUM ⚡</title>
<style>
:root {
  --bg-main: #060913;
  --bg-card: rgba(22, 27, 34, 0.7);
  --border-neon: #00f0ff;
  --border-dim: #1f2d3d;
  --accent-neon: #00f0ff;
  --accent-purple: #9d4edd;
  --danger-neon: #ff0055;
  --text-muted: #707e94;
  --neon-glow: 0 0 10px rgba(0, 240, 255, 0.5), 0 0 20px rgba(0, 240, 255, 0.2);
  --danger-glow: 0 0 10px rgba(255, 0, 85, 0.5), 0 0 20px rgba(255, 0, 85, 0.2);
}

body {
  background: var(--bg-main);
  background-image: 
    linear-gradient(rgba(0, 240, 255, 0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(0, 240, 255, 0.03) 1px, transparent 1px);
  background-size: 30px 30px;
  color: #fff;
  font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
  margin: 0;
  -webkit-font-smoothing: antialiased;
  overflow-x: hidden;
}

/* Animations */
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(20px); filter: blur(5px); }
  to { opacity: 1; transform: translateY(0); filter: blur(0); }
}

@keyframes neonPulse {
  0%, 100% { text-shadow: 0 0 8px rgba(0,240,255,0.6), 0 0 15px rgba(0,240,255,0.3); }
  50% { text-shadow: 0 0 15px rgba(0,240,255,0.9), 0 0 30px rgba(0,240,255,0.5); }
}

@keyframes borderGlow {
  0%, 100% { border-color: rgba(0, 240, 255, 0.3); box-shadow: 0 0 5px rgba(0,240,255,0.1); }
  50% { border-color: rgba(0, 240, 255, 0.8); box-shadow: 0 0 15px rgba(0,240,255,0.3); }
}

.top {
  padding: 25px 20px;
  background: rgba(10, 15, 30, 0.85);
  border-bottom: 2px solid var(--border-neon);
  box-shadow: 0 5px 30px rgba(0, 240, 255, 0.15);
  backdrop-filter: blur(10px);
}

.top h2 {
  margin: 0;
  font-weight: 800;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: #fff;
  animation: neonPulse 3s infinite;
  display: flex;
  align-items: center;
  gap: 12px;
  font-family: 'Courier New', Courier, monospace;
}

.badge-admin {
  background: rgba(255, 0, 85, 0.15);
  color: var(--danger-neon);
  border: 1px solid var(--danger-neon);
  padding: 3px 10px;
  font-size: 11px;
  border-radius: 4px;
  text-transform: uppercase;
  letter-spacing: 2px;
  box-shadow: var(--danger-glow);
  font-weight: bold;
}

.wrap {
  max-width: 1100px;
  margin: auto;
  padding: 30px 20px;
  animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

.box, .cat {
  background: var(--bg-card);
  border: 1px solid var(--border-dim);
  border-radius: 16px;
  margin-bottom: 20px;
  backdrop-filter: blur(8px);
  box-shadow: 0 8px 32px rgba(0,0,0,0.4);
  overflow: hidden;
  transition: all 0.3s ease;
}

.box:hover {
  border-color: rgba(0, 240, 255, 0.4);
  box-shadow: 0 8px 32px rgba(0, 240, 255, 0.05);
}

.box { padding: 25px; }

h1 {
  font-family: 'Courier New', Courier, monospace;
  font-size: 36px;
  text-transform: uppercase;
  letter-spacing: 4px;
  animation: neonPulse 2.5s infinite;
  color: #fff;
}

h3 {
  margin-top: 0;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  color: var(--accent-neon);
}

input, select {
  width: 100%;
  padding: 14px;
  margin: 10px 0;
  background: rgba(5, 8, 15, 0.9);
  color: #fff;
  border: 1px solid var(--border-dim);
  border-radius: 10px;
  box-sizing: border-box;
  font-size: 14px;
  transition: all 0.3s ease;
}

input:focus, select:focus {
  outline: none;
  border-color: var(--accent-neon);
  box-shadow: 0 0 12px rgba(0, 240, 255, 0.3);
  background: rgba(10, 20, 40, 0.9);
}

button {
  padding: 12px 20px;
  margin: 4px;
  border: 1px solid var(--border-dim);
  border-radius: 10px;
  cursor: pointer;
  background: #1a2333;
  color: #a5b5cc;
  font-weight: 600;
  font-size: 14px;
  text-transform: uppercase;
  letter-spacing: 1px;
  transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

button:hover {
  background: #25334d;
  color: #fff;
  transform: translateY(-2px);
}

button:active { transform: translateY(0) scale(0.96); }

.primary {
  background: rgba(0, 240, 255, 0.1);
  color: var(--accent-neon);
  border: 1px solid var(--accent-neon);
}
.primary:hover {
  background: var(--accent-neon);
  color: #000;
  box-shadow: var(--neon-glow);
}

.danger {
  background: rgba(255, 0, 85, 0.1);
  color: var(--danger-neon);
  border: 1px solid var(--danger-neon);
}
.danger:hover {
  background: var(--danger-neon);
  color: #fff;
  box-shadow: var(--danger-glow);
}

.cat {
  border-left: 4px solid var(--accent-purple);
}

.cathead {
  padding: 20px 24px;
  cursor: pointer;
  font-weight: 700;
  background: rgba(20, 26, 38, 0.5);
  display: flex;
  justify-content: space-between;
  align-items: center;
  transition: background 0.3s;
  text-transform: uppercase;
  letter-spacing: 1px;
}
.cathead:hover { background: rgba(30, 40, 60, 0.6); }

.catbody { display: none; background: rgba(8, 12, 20, 0.6); }

.channel {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px 24px;
  border-top: 1px solid var(--border-dim);
  transition: background 0.2s ease;
}
.channel:hover { background: rgba(0, 240, 255, 0.03); }

.channel-info { display: flex; align-items: center; gap: 20px; }

.logo-img { 
  width: 54px; 
  height: 54px; 
  object-fit: contain; 
  background: #02050a; 
  border-radius: 12px; 
  border: 1px solid var(--border-dim);
  transition: transform 0.3s ease;
}
.channel:hover .logo-img {
  transform: scale(1.08);
  border-color: var(--accent-neon);
  box-shadow: var(--neon-glow);
}

.logo-placeholder { 
  width: 54px; 
  height: 54px; 
  display: flex; 
  align-items: center; 
  justify-content: center; 
  background: #02050a; 
  border-radius: 12px; 
  border: 1px solid var(--border-dim); 
  font-size: 24px; 
}

.channel-details b { font-size: 18px; color: #fff; letter-spacing: 0.5px; }
.channel-details small { 
  color: var(--text-muted); 
  font-size: 12px; 
  margin-top: 4px; 
  display: inline-block; 
  font-family: 'Courier New', Courier, monospace;
}

.actions-wrap { display: flex; align-items: center; gap: 10px; }

.stream-count {
  background: rgba(157, 78, 221, 0.15);
  color: #d8bbff;
  padding: 5px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 700;
  border: 1px solid rgba(157, 78, 221, 0.4);
  letter-spacing: 0.5px;
}

/* Modal Styling */
.modal {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(3, 5, 10, 0.85);
  backdrop-filter: blur(8px);
  z-index: 100;
}
.modalbox {
  background: #0b0f19;
  border: 2px solid var(--border-neon);
  padding: 30px;
  max-width: 550px;
  margin: 10% auto;
  border-radius: 20px;
  box-shadow: var(--neon-glow);
  animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}
.modal-title-wrap { display: flex; align-items: center; gap: 20px; margin-bottom: 25px; border-bottom: 1px solid var(--border-dim); padding-bottom: 20px; }
.modal-title-wrap h2 { margin: 0; font-size: 26px; font-family: 'Courier New', Courier, monospace; text-transform: uppercase; }

.feed-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: rgba(5, 8, 15, 0.8);
  padding: 12px 18px;
  border-radius: 12px;
  margin: 10px 0;
  border: 1px solid var(--border-dim);
  transition: border-color 0.2s;
}
.feed-row:hover { border-color: rgba(157, 78, 221, 0.4); }
.feed-info { font-weight: 600; font-size: 14px; letter-spacing: 0.5px; }
.feed-tag { font-size: 11px; background: #9d4edd; padding: 3px 8px; border-radius: 6px; margin-left: 8px; color: #fff; font-weight: bold; }

.loading-screen { 
  text-align: center; 
  padding: 100px; 
  font-size: 22px; 
  color: var(--accent-neon); 
  font-family: 'Courier New', Courier, monospace;
  animation: neonPulse 1.5s infinite;
}
</style>
</head>
<body>
<div id="app"></div>

<script>
const API_LOAD = "index.php?action=load";
const API_SAVE = "index.php?action=save";

const USER="niggerniggerniggerniggerniggerniggerniggerniggerniggerniggerniggerniggerniggerniggerniggernigger";
const ADMIN="niggadmin";

let db = { channels: [] };
let isAdmin = false;

async function loadDB() {
  app.innerHTML = `<div class="loading-screen">⚡ INITIALIZING CYBER STREAM MATRIX... ⚡</div>`;
  try {
    const res = await fetch(API_LOAD);
    db = await res.json() || { channels: [] };
    if(!db.channels) db.channels = [];
    loginScreen();
  } catch (err) {
    console.error(err);
    app.innerHTML = `<div class="loading-screen" style="color:var(--danger-neon)">❌ CONNECTION BREAKDOWN. MATRIX OFFLINE.</div>`;
  }
}

async function saveDB() {
  try {
    await fetch(API_SAVE, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(db)
    });
  } catch (err) {
    console.error("Critical Write Fault:", err);
  }
}

function loginScreen(){
 app.innerHTML = `
 <div class="wrap" style="max-width: 420px; margin-top: 10%;">
   <div class="box" style="text-align: center; padding: 40px; border: 1px solid var(--border-dim); animation: borderGlow 4s infinite;">
     <h1>ELITA // IPTV</h1>
     <p style="color: var(--text-muted); font-size: 13px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 25px;">Enter Terminal Code</p>
     <input id="pw" type="password" placeholder="••••••••••••" style="text-align: center; font-size: 18px; letter-spacing: 4px;">
     <button class="primary" style="width: 100%; padding: 14px; margin-top: 15px;" onclick="login()">Initialize Access</button>
   </div>
 </div>`;
}

function login(){
 const p=pw.value;
 if(p===USER||p===ADMIN){isAdmin=(p===ADMIN);main();}
 else alert("Access Denied: Invalid Security Code Entry");
}

function main(){
 app.innerHTML = `
 <div class="top">
   <div class="wrap" style="padding:0; display:flex; justify-content:space-between; align-items:center;">
     <h2>⚡ ELITA // SYSTEM MATRIX ${isAdmin?`<span class="badge-admin">OVERRIDE</span>`:""}</h2>
     <button class="danger" style="padding: 8px 16px; font-size: 12px;" onclick="location.reload()">Disconnect</button>
   </div>
 </div>
 <div class="wrap">
   ${isAdmin?adminPanel():""}
   <input id="search" style="padding: 16px 20px; font-size: 16px; border-radius: 12px;" placeholder="⚡ Filter data grid by channel name..." oninput="renderCategories()">
   <div id="cats" style="margin-top: 25px;"></div>
 </div>
 <div class="modal" id="modal"><div class="modalbox" id="modalcontent"></div></div>`;
 renderCategories();
}

function adminPanel(){
 return `
 <div class="box" style="border-left: 4px solid var(--accent-neon); background: rgba(10,25,40,0.2);">
   <h3>✨ Node Configuration Interface</h3>
   <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
     <input id="category" placeholder="Network Group (MWE / Poland)">
     <input id="name" placeholder="Channel Node Title">
   </div>
   <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
     <input id="logo" placeholder="Interface Asset Logo URL">
     <input id="uid" placeholder="Index Identifier (channel.index)">
   </div>
   <select id="feedtype"><option>SD</option><option>HD</option></select>
   <button class="primary" style="margin-top: 15px; width: 100%; padding: 14px;" onclick="addChannel()">Compile Node Entry</button>
 </div>`;
}

async function addChannel(){
 const ch={
  category:document.getElementById('category').value.trim(),
  name:document.getElementById('name').value.trim(),
  logo:document.getElementById('logo').value.trim(),
  uid:document.getElementById('uid').value.trim(),
  feed:document.getElementById('feedtype').value,
  streams:[]
 };
 if(!ch.category||!ch.name||!ch.uid){alert("Operational Hazard: Missing parameters");return;}
 
 if(db.channels.some(x => x.uid === ch.uid)) {
   alert("Operational Hazard: Collision detected on Ident Index mapping.");
   return;
 }

 db.channels.push(ch);
 renderCategories();
 await saveDB();
 
 document.getElementById('category').value = '';
 document.getElementById('name').value = '';
 document.getElementById('logo').value = '';
 document.getElementById('uid').value = '';
}

function renderCategories(){
 const q=(document.getElementById('search')?.value||'').toLowerCase();
 const categories=["MWE","Poland"];
 document.getElementById('cats').innerHTML=categories.map(cat=>{
   const list=db.channels.filter(c=>c.category===cat && c.name.toLowerCase().includes(q));
   return `
   <div class="cat">
     <div class="cathead" onclick="toggle('${cat}')">
       <span style="display:flex; align-items:center; gap:10px;">⚡ ${cat} NETWORK</span>
       <span style="color: var(--accent-purple); font-size: 13px; font-family: monospace;">[${list.length} STREAMS ACTIVE]</span>
     </div>
     <div class="catbody" id="cat_${cat}">
       ${list.length === 0 ? `<div style="padding: 30px; color: var(--text-muted); font-size: 14px; text-align:center; font-family: monospace;">// LOG ENTRY EMPTY FOR THIS PATHWAY //</div>` : ''}
       ${list.map(ch=>{
        const logoHtml = ch.logo ? `<img class="logo-img" src="${ch.logo}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"><div class="logo-placeholder" style="display:none">📺</div>` : `<div class="logo-placeholder">📺</div>`;
        return `
        <div class="channel">
          <div class="channel-info">
             ${logoHtml}
             <div class="channel-details">
              <b>${ch.name}</b><br>
              <small>ADDR // ${ch.uid} • SYS // ${ch.feed}</small>
             </div>
          </div>
          <div class="actions-wrap">
            ${ch.streams && ch.streams.length>0?`<span class="stream-count">// ONLINE // x${ch.streams.length}</span>`:''}
            <button onclick="showFeeds('${ch.uid}')">Feeds</button>
            ${isAdmin?`<button class="primary" onclick="addStream('${ch.uid}')">+ Link</button>`:''}
            ${isAdmin?`<button class="danger" style="padding: 12px;" onclick="deleteChannel('${ch.uid}')">🗑️</button>`:''}
          </div>
        </div>`
       }).join('')}
     </div>
   </div>`;
 }).join('');
}

function toggle(cat){
 const e=document.getElementById('cat_'+cat);
 e.style.display=e.style.display==='block'?'none':'block';
}

async function deleteChannel(uid){
 if(confirm("Execute standard destruction payload on chosen mainframe data path?")){
   db.channels = db.channels.filter(x => x.uid !== uid);
   renderCategories();
   await saveDB();
 }
}

async function addStream(uid){
 const feedType = prompt("Assign pipeline target core resolution (SD or HD):").toUpperCase();
 if (feedType !== "SD" && feedType !== "HD") {
   alert("Invalid architecture string.");
   return;
 }

 const s=prompt("Inject raw stream system asset key configuration URL:");
 if(!s)return;
 
 const ch=db.channels.find(x=>x.uid===uid);
 if(!ch.streams) ch.streams = [];
 ch.streams.push({
   url: btoa(s),
   feed: feedType
 });
 renderCategories();
 await saveDB();
}

function showFeeds(uid){
 const ch=db.channels.find(x=>x.uid===uid);
 const logoHtml = ch.logo ? `<img class="logo-img" src="${ch.logo}" onerror="this.style.display='none'">` : '';
 
 let h= `
 <div class="modal-title-wrap">
   ${logoHtml}
   <div>
     <h2>${ch.name}</h2>
     <span style="color: var(--accent-neon); font-size:12px; font-family: monospace;">TARGET_INDEX // ${ch.uid}</span>
   </div>
 </div>`;
 
 if(!ch.streams || ch.streams.length===0){
   h+="<p style='color: var(--danger-neon); text-align: center; padding: 20px 0; font-family: monospace;'>[CRITICAL INFO: SYSTEM CORRIDOR UNLINKED]</p>";
 } else {
   ch.streams.forEach((s,i)=>{
      const streamUrl = typeof s === 'string' ? s : s.url;
      const streamFeed = typeof s === 'object' ? s.feed : 'Legacy';
      h+=`
      <div class="feed-row">
        <div class="feed-info">
          PIPELINE INTERFACE #${i+1} <span class="feed-tag">${streamFeed}</span>
        </div>
        <div>
          <button class="primary" style="padding: 8px 14px; font-size:12px;" onclick="copyFeed('${streamUrl}')">Extract Link</button>
          ${isAdmin?`<button class="danger" style="padding: 8px 12px; font-size:12px;" onclick="deleteStream('${ch.uid}', ${i})">Purge</button>`:''}
        </div>
      </div>`;
   });
 }
 h+=`<div style="text-align: right; margin-top: 25px;"><button style="padding: 10px 24px;" onclick="closeModal()">Close Terminal</button></div>`;
 modalcontent.innerHTML=h;
 modal.style.display='block';
}

async function deleteStream(chUid, streamIndex){
 if(confirm("Execute erase block array formatting sequence on target asset node stream link?")){
   const ch = db.channels.find(x => x.uid === chUid);
   if(ch && ch.streams){
     ch.streams.splice(streamIndex, 1);
     renderCategories();
     showFeeds(chUid);
     await saveDB();
   }
 }
}

function copyFeed(s){
 navigator.clipboard.writeText(atob(s));
 alert("Transmission stream string array cached cleanly to operating memory block.");
}

function closeModal(){modal.style.display='none';}

// Fire system init
loadDB();
</script>
</body>
</html>
