// public/assets/js/firebase-init.js
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-app.js";
import { getFirestore } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-firestore.js";
import { getAuth } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-auth.js"; // Tambahan

// Import the functions you need from the SDKs you need
// import { initializeApp } from "firebase/app";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
const firebaseConfig = {
  apiKey: "AIzaSyDqE5VxK2kmXNiKduLBMzSQc1oI9347Kiw",
  authDomain: "ppi-94-pkj.firebaseapp.com",
  projectId: "ppi-94-pkj",
  storageBucket: "ppi-94-pkj.firebasestorage.app",
  messagingSenderId: "140599266448",
  appId: "1:140599266448:web:3a7d72b2727d4ed00e1c15"
};

// Initialize Firebase
// const app = initializeApp(firebaseConfig);

// Initialize Firebase
const app = initializeApp(firebaseConfig);
export const db = getFirestore(app);
export const auth = getAuth(app); // Tambahan