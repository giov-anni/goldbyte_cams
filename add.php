<?php include 'includes/header.php'; ?>

<style>
    /* Security & UI Enhancements */
    .strength-wrapper { margin-top: 10px; }
    .strength-bar { height: 6px; width: 100%; background: #e2e8f0; border-radius: 10px; overflow: hidden; }
    #meter { height: 100%; width: 0; transition: all 0.4s ease; }
    #strength-text { font-size: 0.7rem; font-weight: 700; margin-top: 5px; display: block; text-transform: uppercase; letter-spacing: 0.5px; }
    
    .toggle-icon { position: absolute; right: 15px; top: 40px; cursor: pointer; color: #64748b; transition: 0.2s; }
    .toggle-icon:hover { color: #2563eb; }
    .toggle-icon svg { width: 20px; height: 20px; }

    /* Conditional Address Field */
    #address-section { display: none; margin-bottom: 1.5rem; animation: fadeIn 0.4s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

    .form-group label { display: block; font-weight: 600; color: #475569; margin-bottom: 8px; }
    input[type="text"], input[type="email"], input[type="password"], input[type="datetime-local"], select, textarea {
        width: 100%; padding: 0.8rem; border-radius: 10px; border: 1px solid #cbd5e1; outline-color: #2563eb;
    }
</style>

<div class="form-wrapper" style="max-width: 800px; margin: 4rem auto; background: white; padding: 3rem; border-radius: 24px; box-shadow: 0 15px 35px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;">
    <div class="form-header" style="text-align: center; margin-bottom: 2.5rem;">
        <h2 style="color: #0f172a; font-weight: 800; font-size: 2rem;">Secure Patient Registration</h2>
        <p style="color: #64748b;">Schedule your appointment and create your medical portal in one step.</p>
    </div>

    <form id="patientForm" action="process_patient.php" method="POST" onsubmit="return validateForm()">
        
        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" id="fname" name="first_name" placeholder="Enter first name" required onkeyup="updateValidation()">
            </div>
            <div class="form-group">
                <label>Surname</label>
                <input type="text" id="sname" name="surname" placeholder="Enter surname" required onkeyup="updateValidation()">
            </div>
        </div>

        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="email@example.com" required>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" placeholder="e.g. 024XXXXXXX" required>
            </div>
        </div>

        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label>Gender</label>
                <div class="radio-group" style="display: flex; gap: 20px; padding-top: 10px;">
                    <label style="font-weight: 400;"><input type="radio" name="gender" value="Male" required> Male</label>
                    <label style="font-weight: 400;"><input type="radio" name="gender" value="Female"> Female</label>
                </div>
            </div>
            <div class="form-group">
                <label>Required Specialty</label>
                <select name="specialty_id" required>
                    <option value="">-- Select Specialist --</option>
                    <option value="1">General Purpose</option>
                    <option value="2">Dentist</option>
                    <option value="3">Optometrist</option>
                    <option value="4">Gynecologist</option>
                    <option value="5">Pediatrician</option>
                </select>
            </div>
        </div>

        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group" style="position: relative;">
                <label>Create Password</label>
                <input type="password" id="pwd" name="password" placeholder="Min. 8 characters" required onkeyup="checkStrength(this.value)">
                <div class="toggle-icon" onclick="togglePass('pwd', this)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <div class="strength-wrapper">
                    <div class="strength-bar"><div id="meter"></div></div>
                    <span id="strength-text" style="color: #94a3b8;">Security: Waiting...</span>
                </div>
            </div>
            <div class="form-group" style="position: relative;">
                <label>Confirm Password</label>
                <input type="password" id="cpwd" name="confirm_password" placeholder="Repeat password" required>
                <div class="toggle-icon" onclick="togglePass('cpwd', this)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
            </div>
        </div>

        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label>Service Type</label>
                <div class="radio-group" style="display: flex; gap: 20px; padding-top: 10px;">
                    <label style="font-weight: 400;">
                        <input type="radio" name="service_type" value="In-Clinic" checked onchange="toggleAddress(false)"> In-Clinic
                    </label>
                    <label style="font-weight: 400;">
                        <input type="radio" name="service_type" value="Home-Service" onchange="toggleAddress(true)"> Home-Service (+100)
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label>Preferred Date & Time</label>
                <input type="datetime-local" id="appointment_date" name="appointment_date" required>
            </div>
        </div>

        <div id="address-section">
            <label style="color: #1e3a8a;">Residential Address / Landmark (For Home Visit)</label>
            <textarea name="home_address" id="home_address" rows="3" placeholder="Enter your full digital address or nearby landmark in Winneba"></textarea>
        </div>

        <div class="checkbox-group" style="margin-bottom: 1.5rem; padding: 1rem; background: #fff1f2; border-radius: 12px; border: 1px solid #fecaca;">
            <input type="checkbox" id="emergencyCheck" name="is_emergency" value="1" onchange="calculateFee()">
            <label for="emergencyCheck" style="color: #b91c1c; font-weight: 700; cursor: pointer; margin-left: 8px;">
                🚨 MARK AS EMERGENCY (+200 GH₵)
            </label>
        </div>

        <div class="fee-display" style="background: #eff6ff; border: 2px dashed #bfdbfe; color: #1e3a8a; padding: 1.5rem; border-radius: 16px; text-align: center; margin-bottom: 2rem;">
            <h3 style="font-size: 1rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 1px;">Estimated Consultation Fee</h3>
            <div class="amount" style="font-size: 2.5rem; font-weight: 800; color: #2563eb;">GH₵ <span id="totalAmount">100.00</span></div>
        </div>

        <div style="background: #f8fafc; padding: 1.2rem; border-radius: 12px; margin-bottom: 2rem; font-size: 0.85rem; color: #475569; border: 1px solid #e2e8f0; display: flex; gap: 12px; align-items: flex-start;">
            <input type="checkbox" id="consent" required style="margin-top: 4px; width: 18px; height: 18px; cursor: pointer;">
            <label for="consent" style="cursor: pointer;">
                I agree to the <strong>Clinical Terms of Service</strong>. I understand that GoldByte CAMS will create a secure, encrypted medical profile for my treatment history.
            </label>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1.2rem; background: #2563eb; color: white; border: none; border-radius: 14px; font-weight: 700; font-size: 1.1rem; cursor: pointer; transition: 0.3s; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);">
            Finalize Registration & Book Appointment
        </button>
    </form>
</div>

<script>
// Toggle Password with SVG Icons
function togglePass(id, el) {
    const input = document.getElementById(id);
    const isPass = input.type === "password";
    input.type = isPass ? "text" : "password";
    el.innerHTML = isPass 
        ? '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>'
        : '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>';
}

// Password Strength Meter Logic
function checkStrength(password) {
    let strength = 0;
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
    if (password.match(/\d/)) strength++;
    if (password.match(/[^a-zA-Z\d]/)) strength++;
    
    const meter = document.getElementById('meter');
    const text = document.getElementById('strength-text');
    
    switch(strength) {
        case 0: meter.style.width = '0'; text.innerText = 'Too Short'; text.style.color = '#94a3b8'; break;
        case 1: meter.style.width = '25%'; meter.style.background = '#ef4444'; text.innerText = 'Weak'; text.style.color = '#ef4444'; break;
        case 2: meter.style.width = '50%'; meter.style.background = '#f59e0b'; text.innerText = 'Fair'; text.style.color = '#f59e0b'; break;
        case 3: meter.style.width = '75%'; meter.style.background = '#2563eb'; text.innerText = 'Good'; text.style.color = '#2563eb'; break;
        case 4: meter.style.width = '100%'; meter.style.background = '#10b981'; text.innerText = 'Strong'; text.style.color = '#10b981'; break;
    }
}

// Handle Home Service Address Visibility
function toggleAddress(show) {
    const section = document.getElementById('address-section');
    const input = document.getElementById('home_address');
    section.style.display = show ? 'block' : 'none';
    input.required = show;
    calculateFee();
}

function calculateFee() {
    let total = 100; // Base
    const serviceType = document.querySelector('input[name="service_type"]:checked').value;
    if(serviceType === 'Home-Service') total += 100;
    if(document.getElementById('emergencyCheck').checked) total += 200;
    document.getElementById('totalAmount').innerText = total.toFixed(2);
}

function validateForm() {
    const fname = document.getElementById('fname').value.trim().toLowerCase();
    const sname = document.getElementById('sname').value.trim().toLowerCase();
    const pwd = document.getElementById('pwd').value;
    const cpwd = document.getElementById('cpwd').value;
    const dateInput = document.getElementById('appointment_date').value;
    const isEmergency = document.getElementById('emergencyCheck').checked;
    const serviceType = document.querySelector('input[name="service_type"]:checked').value;

    if (pwd.length < 8) { alert("Security: Password must be at least 8 characters."); return false; }
    if (fname !== "" && pwd.toLowerCase().includes(fname)) { alert("Security: Password cannot contain your first name."); return false; }
    if (pwd !== cpwd) { alert("Validation: Passwords do not match."); return false; }

    const selectedDate = new Date(dateInput);
    if (selectedDate < new Date()) { alert("Error: Appointment cannot be in the past."); return false; }

    // Working Hours Check (Skip for Emergency/Home-Service)
    if (!isEmergency && serviceType !== "Home-Service") {
        const hour = selectedDate.getHours();
        if (hour < 8 || hour >= 16) {
            alert("Clinic Hours (Mon-Fri): 08:00am - 04:00pm. Select Emergency for 24/7 service.");
            return false;
        }
    }
    return true;
}
</script>

<?php include 'includes/footer.php'; ?>