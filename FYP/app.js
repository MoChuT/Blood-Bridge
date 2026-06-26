function showToast(title, body) {
  const toast = document.querySelector(".toast");
  if (!toast) return;
  toast.innerHTML = `<strong>${title}</strong><br><span>${body}</span>`;
  toast.classList.add("show");
  clearTimeout(window.toastTimer);
  window.toastTimer = setTimeout(() => toast.classList.remove("show"), 2800);
}

document.addEventListener("click", (event) => {
  const node = event.target.closest(".node");
  if (node && node.getAttribute("href")) {
    event.preventDefault();
    window.location.href = node.getAttribute("href");
    return;
  }

  const nodeByPosition = Array.from(document.querySelectorAll(".node")).find((item) => {
    const rect = item.getBoundingClientRect();
    return event.clientX >= rect.left &&
      event.clientX <= rect.right &&
      event.clientY >= rect.top &&
      event.clientY <= rect.bottom;
  });

  if (nodeByPosition && nodeByPosition.getAttribute("href")) {
    event.preventDefault();
    window.location.href = nodeByPosition.getAttribute("href");
    return;
  }

  const action = event.target.closest("[data-action]");
  if (!action) return;

  const messages = {
    login: ["Login verified", "Donor account has been verified successfully."],
    register: ["Registration submitted", "New donor record has been created."],
    screening: ["Screening saved", "Eligibility status has been updated."],
    upload: ["Document uploaded", "Document storage record has been created."],
    appointment: ["Appointment requested", "Appointment record is waiting for confirmation."],
    alert: ["Matching alert sent", "Suitable donors have been notified."],
    inventory: ["Inventory updated", "Blood inventory record has been saved."],
    approval: ["Decision saved", "Appointment status has been updated."],
    announcement: ["Announcement saved", "Announcement record has been updated."],
    donation: ["Donation record saved", "Donation result has been stored."]
  };

  const message = messages[action.dataset.action];
  if (message) showToast(message[0], message[1]);
});

function initThreeSyringe() {
  const mount = document.getElementById("threeScene");
  if (!mount || !window.THREE) return;

  const scene = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(38, window.innerWidth / window.innerHeight, .1, 100);
  camera.position.set(0, 1.3, 10);

  const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.setSize(window.innerWidth, window.innerHeight);
  mount.appendChild(renderer.domElement);

  scene.add(new THREE.AmbientLight(0xffffff, .68));
  const key = new THREE.DirectionalLight(0xffffff, 1.5);
  key.position.set(4, 6, 5);
  scene.add(key);
  const redLight = new THREE.PointLight(0xff2d44, 1.3, 18);
  redLight.position.set(-3, 2, 4);
  scene.add(redLight);

  const syringe = new THREE.Group();
  syringe.rotation.z = -.28;
  scene.add(syringe);

  const glass = new THREE.MeshPhysicalMaterial({
    color: 0xf3fbff,
    transparent: true,
    opacity: .36,
    roughness: .1,
    metalness: .05,
    clearcoat: .8
  });
  const blood = new THREE.MeshStandardMaterial({
    color: 0xc82435,
    roughness: .28,
    metalness: .02,
    emissive: 0x2b0206,
    emissiveIntensity: .08
  });
  const metal = new THREE.MeshStandardMaterial({
    color: 0xd8e1dc,
    roughness: .32,
    metalness: .55
  });
  const dark = new THREE.MeshStandardMaterial({
    color: 0x5a5f62,
    roughness: .52,
    metalness: .18
  });

  const barrel = new THREE.Mesh(new THREE.CylinderGeometry(.62, .62, 3.7, 48), glass);
  barrel.rotation.z = Math.PI / 2;
  syringe.add(barrel);

  const fill = new THREE.Mesh(new THREE.CylinderGeometry(.49, .49, 2.45, 48), blood);
  fill.rotation.z = Math.PI / 2;
  fill.position.x = -.35;
  syringe.add(fill);

  const plunger = new THREE.Mesh(new THREE.CylinderGeometry(.22, .22, 2.4, 28), metal);
  plunger.rotation.z = Math.PI / 2;
  plunger.position.x = -2.9;
  syringe.add(plunger);

  const handle = new THREE.Mesh(new THREE.BoxGeometry(.18, 1.75, .18), dark);
  handle.position.x = -4.1;
  syringe.add(handle);

  const stopper = new THREE.Mesh(new THREE.CylinderGeometry(.58, .58, .28, 48), dark);
  stopper.rotation.z = Math.PI / 2;
  stopper.position.x = -1.92;
  syringe.add(stopper);

  const hub = new THREE.Mesh(new THREE.CylinderGeometry(.34, .25, .72, 32), metal);
  hub.rotation.z = Math.PI / 2;
  hub.position.x = 2.22;
  syringe.add(hub);

  const needle = new THREE.Mesh(new THREE.CylinderGeometry(.035, .018, 2.55, 18), metal);
  needle.rotation.z = Math.PI / 2;
  needle.position.x = 3.78;
  syringe.add(needle);

  const tip = new THREE.Mesh(new THREE.ConeGeometry(.065, .28, 18), metal);
  tip.rotation.z = -Math.PI / 2;
  tip.position.x = 5.17;
  syringe.add(tip);

  function resize() {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
  }

  window.addEventListener("resize", resize);

  function animate(time) {
    requestAnimationFrame(animate);
    const t = time * .001;
    syringe.rotation.y = Math.sin(t * .7) * .18;
    syringe.position.y = .72 + Math.sin(t * 1.4) * .08;
    redLight.intensity = 1.15 + Math.sin(t * 2) * .18;
    renderer.render(scene, camera);
  }

  animate(0);
}

window.addEventListener("load", initThreeSyringe);

function initIndexSnapScroll() {
  if (!document.body.classList.contains("snap-page")) return;

  const sections = Array.from(document.querySelectorAll("main > section"));
  if (sections.length < 2) return;

  let currentIndex = 0;
  let locked = false;

  function nearestSectionIndex() {
    const scrollY = window.scrollY;
    let nearest = 0;
    let nearestDistance = Infinity;

    sections.forEach((section, index) => {
      const distance = Math.abs(section.offsetTop - scrollY);
      if (distance < nearestDistance) {
        nearestDistance = distance;
        nearest = index;
      }
    });

    return nearest;
  }

  window.addEventListener("wheel", (event) => {
    if (locked) {
      event.preventDefault();
      return;
    }

    const direction = event.deltaY > 0 ? 1 : -1;
    currentIndex = nearestSectionIndex();
    const nextIndex = Math.max(0, Math.min(sections.length - 1, currentIndex + direction));

    if (nextIndex === currentIndex) return;

    event.preventDefault();
    locked = true;
    currentIndex = nextIndex;
    sections[currentIndex].scrollIntoView({ behavior: "smooth", block: "start" });

    window.setTimeout(() => {
      locked = false;
    }, 850);
  }, { passive: false });
}

window.addEventListener("load", initIndexSnapScroll);
