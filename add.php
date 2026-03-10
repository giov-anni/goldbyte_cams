<?php include 'includes/header.php'; ?>

<div class="form-wrapper">
    <div class="form-header">
        <h2>Book an Appointment</h2>
        <p>Register as a patient to schedule your appointment with us.</p>
    </div>

    <form id="patientForm" action="process_patient.php" method="POST" onsubmit="return validateForm()">
        
        <div class="form-row">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" id="fname" name="first_name" placeholder="Enter first name" required>
            </div>
            <div class="form-group">
                <label>Surname</label>
                <input type="text" id="sname" name="surname" placeholder="Enter surname" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="email@example.com" required>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" placeholder="e.g. 024XXXXXXX" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Gender</label>
                <div class="radio-group">
                    <label class="radio-item"><input type="radio" name="gender" value="Male" required> Male</label>
                    <label class="radio-item"><input type="radio" name="gender" value="Female"> Female</label>
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

        <div class="form-row">
            <div class="form-group" style="position: relative;">
                <label>Create Password</label>
                <input type="password" id="pwd" name="password" placeholder="Min. 8 characters" required>
                <span onclick="togglePass('pwd')" style="position: absolute; right: 15px; top: 40px; cursor: pointer; font-size: 1.2rem;" title="Toggle Visibility">👁️</span>
            </div>
            <div class="form-group" style="position: relative;">
                <label>Confirm Password</label>
                <input type="password" id="cpwd" name="confirm_password" placeholder="Repeat password" required>
                <span onclick="togglePass('cpwd')" style="position: absolute; right: 15px; top: 40px; cursor: pointer; font-size: 1.2rem;" title="Toggle Visibility">👁️</span>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Service Type</label>
                <div class="radio-group">
                    <label class="radio-item">
                        <input type="radio" name="service_type" value="In-Clinic" checked onchange="calculateFee()"> In-Clinic
                    </label>
                    <label class="radio-item">
                        <input type="radio" name="service_type" value="Home-Service" onchange="calculateFee()"> Home-Service (+100 GH₵)
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label>Appointment Date & Time</label>
                <input type="datetime-local" id="appointment_date" name="appointment_date" required>
            </div>
        </div>

        <div class="checkbox-group" style="margin-bottom: 1.5rem;">
            <input type="checkbox" id="emergencyCheck" name="is_emergency" value="1" onchange="calculateFee()">
            <label for="emergencyCheck" style="color: #b91c1c; font-weight: 600; cursor: pointer;">
                EMERGENCY: Priority Booking (+200 GH₵)
            </label>
        </div>

        <div class="fee-display" style="background: #eff6ff; border: 2px dashed #bfdbfe; color: #1e3a8a; padding: 1.5rem; border-radius: 8px; text-align: center; margin-bottom: 2rem;">
            <h3 style="font-size: 1.1rem; margin-bottom: 0.5rem;">Total Estimated Fee</h3>
            <div class="amount" style="font-size: 2.2rem; font-weight: 700; color: #2563eb;">GH₵ <span id="totalAmount">100.00</span></div>
        </div>

        <div style="background: #f1f5f9; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; font-size: 0.85rem; color: #475569; border: 1px solid #cbd5e1; line-height: 1.5;">
            <div style="display: flex; gap: 12px; align-items: flex-start;">
                <input type="checkbox" id="consent" required style="margin-top: 5px; width: 18px; height: 18px; cursor: pointer;">
                <label for="consent" style="cursor: pointer;">
                    I agree to the <strong>Terms & Conditions</strong>. By checking this, I consent to GoldByte CAMS creating a secure account for me. I understand that my data will be used <strong>strictly for healthcare purposes</strong>.
                </label>
            </div>
        </div>

        <div style="text-align: center;">
            <button type="submit" class="btn btn-primary" style="min-width: 280px; padding: 1rem; font-size: 1.1rem; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);">
                Register & Book Appointment
            </button>
        </div>
    </form>
</div>

<script>
function togglePass(id) {
    const input = document.getElementById(id);
    input.type = input.type === "password" ? "text" : "password";
}

function calculateFee() {
    let baseFee = 100;
    let total = baseFee;
    const serviceType = document.querySelector('input[name="service_type"]:checked').value;
    if(serviceType === 'Home-Service') total += 100;
    const isEmergency = document.getElementById('emergencyCheck').checked;
    if(isEmergency) total += 200;
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

    // 1. Password Security Checks
    if (pwd.length < 8) { alert("Password must be at least 8 characters."); return false; }
    if (fname !== "" && pwd.toLowerCase().includes(fname)) { alert("Password cannot contain your first name."); return false; }
    if (sname !== "" && pwd.toLowerCase().includes(sname)) { alert("Password cannot contain your surname."); return false; }
    if (pwd !== cpwd) { alert("Passwords do not match."); return false; }

    // 2. Date & Working Hours Logic
    const selectedDate = new Date(dateInput);
    const now = new Date();

    if (selectedDate < now) {
        alert("Selection Error: You cannot book an appointment in the past.");
        return false;
    }

    // skip working hours check if Emergency or Home Service is active (24/7)
    if (!isEmergency && serviceType !== "Home-Service") {
        const day = selectedDate.getDay(); // 0 = Sunday, 6 = Saturday
        const hour = selectedDate.getHours();

        if (day >= 1 && day <= 5) { // Monday - Friday
            if (hour < 8 || hour >= 16) {
                alert("Clinic Hours (Mon-Fri): 08:00am - 04:00pm. For 24/7 service, please select Emergency or Home-Service.");
                return false;
            }
        } else { // Saturday & Sunday
            if (hour < 12 || hour >= 16) {
                alert("Clinic Hours (Weekend): 12:00pm - 04:00pm. For 24/7 service, please select Emergency or Home-Service.");
                return false;
            }
        }
    }

    return true;
}
</script>

<?php include 'includes/footer.php'; ?>