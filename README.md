# PayPoint 💳 (v2.0-Stable)

**PayPoint** is a robust bill payment web application built with **Laravel**. It allows users to manage a digital wallet, perform real-time balance checks, and purchase essential services like Airtime and Data with high security.

---

### ✅ Core Features (Currently Live)
* **Wallet System:** Real-time balance fetching with standardized account numbering (e.g., `SB-00000002`).
* **Airtime Purchase:** Instant top-up for MTN, Airtel, Glo, and 9mobile.
* **Data Bundles:** Smart two-step plan selection fetching live data via API.
* **Security:** * 4-Digit Transaction PIN verification for all purchases.
    * Direct-to-DB balance synchronization (fixed the ₦0 balance display error).
* **UI/UX:** Clean, mobile-first "PayPoint Blue" design, optimized for Android devices like the **Infinix Note 8**.

---

### 🛠 Tech Stack
* **Backend:** Laravel 11 (PHP 8.2+)
* **Database:** MySQL (Relational tracking of Users and Transactions)
* **Frontend:** Blade Templates + Custom CSS (Mobile-responsive)
* **Integrations:** VTPass (Sandbox & Production ready)

---

### 🚀 Local Setup & Installation

**1. Clone & Install**
```bash
git clone [https://github.com/tobyishiwu/paypoint.git](https://github.com/tobyishiwu/paypoint.git)
cd paypoint
composer install
npm install && npm run build
