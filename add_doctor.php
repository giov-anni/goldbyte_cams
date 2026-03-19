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

    .form-group label { display: block; font-weight: 600; color: #475569; margin-bottom: 8px; }
    input[type="text"], input[type="email"], input[type="password"], select, textarea, input[type="file"] {
        width: 100%; padding: 0.8rem; border-radius: 10px; border: 1px solid #cbd5e1; outline-color: #2563eb; font-family: inherit;
    }
    textarea { resize: vertical; min-height: 120px; }
    input[type="file"] { background: #f8fafc; cursor: pointer; }
</style>

<div class="form-wrapper" style="max-width: 850px; margin: 4rem auto; background: white; padding: 3rem; border-radius: 24px; box-shadow: 0 15px 35px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;">
    <div class="form-header" style="text-align: center; margin-bottom: 2.5rem;">
        <h2 style="color: #0f172a; font-weight: 800; font-size: 2rem;">Doctor Onboarding</h2>
        <p style="color: #64748b;">Apply to join the GoldByte CAMS medical network in Winneba.</p>
    </div>

    <form id="doctorForm" action="process_doctor.php" method="POST" enctype="multipart/form-data" onsubmit="return validateDoctorForm()">
        
        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" placeholder="e.g. Samuel" required>
            </div>
            <div class="form-group">
                <label>Surname</label>
                <input type="text" name="surname" placeholder="e.g. Mensah" required>
            </div>
        </div>

        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="doctor@goldbyte.com" required>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" placeholder="e.g. 024XXXXXXX" required>
            </div>
        </div>

        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group" style="position: relative;">
                <label>Password</label>
                <input type="password" id="dpwd" name="password" placeholder="Create secure password" required onkeyup="checkStrength(this.value)">
                <div class="toggle-icon" onclick="togglePass('dpwd', this)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <div class="strength-wrapper">
                    <div class="strength-bar"><div id="meter"></div></div>
                    <span id="strength-text" style="color: #94a3b8;">Security: Waiting...</span>
                </div>
            </div>
            <div class="form-group" style="position: relative;">
                <label>Confirm Password</label>
                <input type="password" id="dcpwd" name="confirm_password" placeholder="Repeat password" required>
                <div class="toggle-icon" onclick="togglePass('dcpwd', this)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label>Gender</label>
            <div class="radio-group" style="display: flex; gap: 25px; padding-top: 5px;">
                <label style="font-weight: 400; cursor: pointer;"><input type="radio" name="gender" value="Male" required> Male</label>
                <label style="font-weight: 400; cursor: pointer;"><input type="radio" name="gender" value="Female"> Female</label>
            </div>
        </div>

        <div style="margin: 2.5rem 0; border-top: 2px dashed #e2e8f0; position: relative;">
            <span style="position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: white; padding: 0 15px; color: #94a3b8; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Professional Credentials</span>
        </div>

        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label>Your Specialty</label>
                <select name="specialty_id" required>
                    <option value="">-- Select Specialty --</option>
                    <option value="1">General Purpose</option>
                    <option value="2">Dentist</option>
                    <option value="3">Optometrist</option>
                    <option value="4">Gynecologist</option>
                    <option value="5">Pediatrician</option>
                </select>
            </div>
            <div class="form-group">
                <label>Medical License Number</label>
                <input type="text" name="license_number" placeholder="e.g. MDC/RN/XXXX" required>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label>Upload CV (PDF format only)</label>
            <input type="file" name="cv_file" accept=".pdf" required>
            <small style="color: #64748b; display: block; margin-top: 5px;">Upload your latest medical certifications and experience record.</small>
        </div>

        <div class="form-group" style="margin-bottom: 2rem;">
            <label>Professional Bio</label>
            <textarea name="bio" placeholder="Describe your medical philosophy, qualifications, and previous clinical practice experience..." required></textarea>
        </div>

        <div style="text-align: center;">
            <button type="submit" style="
                background: #0f172a; 
                color: white;
                padding: 1.2rem 4rem; 
                border-radius: 14px; 
                width: 100%; 
                cursor: pointer; 
                border: none; 
                font-size: 1.1rem;
                font-weight: 700; 
                transition: all 0.3s ease;
                box-shadow: 0 10px 20px -5px rgba(15, 23, 42, 0.3);
            " onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                Finalize Application & Join Network
            </button>
        </div>
    </form>
</div>

<script>
// Toggle Password Visibility
function togglePass(id, el) {
    const input = document.getElementById(id);
    const isPass = input.type === "password";
    input.type = isPass ? "text" : "password";
    el.innerHTML = isPass 
        ? '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>'
        : '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>';
}

// Password Strength Meter
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

function validateDoctorForm() {
    const pwd = document.getElementById('dpwd').value;
    const cpwd = document.getElementById('dcpwd').value;
    
    if (pwd.length < 8) {
        alert("Security: Password must be at least 8 characters.");
        return false;
    }
    if (pwd !== cpwd) {
        alert("Validation: Passwords do not match.");
        return false;
    }
    return true;
}
</script>

<?php include 'includes/footer.php'; ?>