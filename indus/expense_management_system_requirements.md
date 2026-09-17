# Expense & Settlement Management System ("Indus Gas")

## 1. Executive Summary & Problem Statement

### 1.1 Business Context

**Indus Gas** relies on daily operational field trips, gas cylinder filling, logistics, and warehouse management in Pakistan. All financial values and accounting in this system are recorded in **Rupees (PKR)**. Daily operations involve three main actors who incur or settle business expenses:

1. **Usman** (Partner / Primary Funder - pays \~95% of business expenses and settles field out-of-pocket costs)

2. **Bilal Bhatti** (Partner - pays \~5% of business expenses directly)

3. **Younus** (Warehouse Manager - pays field/operational expenses out-of-pocket during daytime delivery runs)

### 1.2 Current Pain Points & Challenges

- **Uncertain Reimbursement Status (Younus):** Due to daily busy schedules, Usman often forgets whether Younus's out-of-pocket expenses were cleared on the same day or remain pending. Reimbursements are frequently paid on the next day or after a multi-day delay, leading to manual confusion and potential double-payments or missed payments.

- **Untracked Partner Settlements (Bilal):** Usman and Bilal frequently lose track of whether net daily balance settlements between partners have been transferred/paid out or remain unpaid across multiple days.

- **Manual Math & Formulas Burden:** Usman and the team currently rely on manual calculation formulas (halving expenses, subtracting offsets, tracking advances). This leads to human error and unnecessary effort at the end of exhausting field days.

- **Fragmented WhatsApp Tracking:** Expense reports sent via WhatsApp groups lack status indicators (e.g., "Paid", "Pending", "Partially Settled"), making audit and history lookup difficult.

- **Lack of Visibility:** No real-time dashboard exists to show overall spend trends, daily dynamic totals, spend breakdown per individual, or remaining monthly operational budgets.

## 2. As-Is Workflow (Current Operational Process)

### Step 1: Daily Field Operations (09:00 AM – End of Deliveries)

- Driver (Rauf) and Warehouse Manager (Younus) load cylinders and visit the filling plant (e.g., BBN Plant).

- Younus pays plant filling charges out of his own pocket (e.g., 10 Rupees/cylinder).

- During supply deliveries, Younus pays all incidental operational expenses out of pocket:
    - Cylinder filling charges

    - Toll taxes (e.g., Ring Road)

    - Society entry fees (20, 30, or 50 Rupees)

    - Vehicle tyre maintenance (air/punctures)

    - Traffic challans / fines

### Step 2: Evening Field Settlement & Delay Handling

- Upon delivery completion, Younus posts his expense breakdown in the WhatsApp group.

- Usman visits the warehouse and clears Younus in cash. **Pain Point:** If Usman cannot pay on the same day, the reimbursement carries over to the next day or later, but currently, there is no system tracking whether Younus is paid or pending.

### Step 3: Partner Expenses & End-of-Day Accounting

- **Usman incurs direct business expenses** (e.g., bulk warehouse purchases, driver daily wage of 1,500 Rupees, staff food allowance, vehicle fuel).

- **Bilal incurs direct business expenses** (e.g., vehicle fuel, meals during field duties, legal/consultant fees).

- **Example Daily Scenario (in Rupees):**
    1. _Younus spends out-of-pocket:_ 20 Rupees (Filling: 10, Toll: 5, Society: 5).

    2. _Usman spends directly:_ 70 Rupees (Fuel: 50 paid to Younus, Stationery: 20).

    3. _Bilal spends directly:_ 10 Rupees (Car Fuel: 5, Dinner: 3, Lawyer: 2).

    4. _Out-of-pocket Settlement:_ Usman pays Younus 20 Rupees cash (Total cash spent by Usman today = 70 + 20 = 90 Rupees).

    5. _Automatic 50/50 Partner Calculation:_
        - Usman's half share: $\frac{90}{2} = 45\text{ Rupees}$

        - Bilal's half share: $\frac{10}{2} = 5\text{ Rupees}$

        - Net Settlement: Bilal must pay Usman $45 - 5 = 40\text{ Rupees}$.

## 3. Core Business Requirements & UX Principles

### 3.1 Design Principles: Minimum Input, Maximum Output

- **Zero Math Requirement for Users:** The user must never calculate formulas, split percentages, or net balances manually. The system must perform all math automatically upon simple data entry.

- **Extremely User-Friendly & Intuitive:** Designed for quick entry on mobile or desktop with minimal clicks/taps so field managers and partners can enter expenses in seconds.

- **Currency Standard:** All amounts in the application strictly use **Rupees (PKR)**.

### 3.2 Reimbursement & Settlement Tracking Engine

- **Younus Reimbursement Status:** Every expense incurred by Younus must feature a clear visual badge:
    - `Pending Reimbursement` (Unpaid to Younus)

    - `Cleared / Reimbursed` (Paid by Usman or Bilal to Younus, including date of settlement)

- **Partner Settlement Ledger (Usman vs. Bilal):**
    - System maintains a running account balance between partners.

    - Clear indicator showing: **"Bilal Owes Usman: X Rupees"** or **"Usman Owes Bilal: X Rupees"**.

    - Ability to mark partner settlements as `Settled / Paid` with a single click, resetting or adjusting the pending balance regardless of how many days have passed.

### 3.3 Cash Advance Tracking

- System records cash advances given to Younus by either Usman or Bilal, automatically offsetting against Younus's out-of-pocket expenses.

## 4. Functional Requirements

### 4.1 Navigation & Placement

- A dedicated **Expenses** menu item placed under the existing **"Indus Gas"** main navigation menu.

### 4.2 Analytics Dashboard (Stats Cards in Rupees)

Top of the page must feature visual, color-coded status cards displaying key metrics (all figures in Rupees):

1. **Today Expense:** Total spend today across all actors.

2. **Yesterday Expense:** Total spend yesterday.

3. **Last 7 Days Expense:** Rolling 7-day total spend.

4. **Last 30 Days Expense:** Rolling 30-day total spend.

5. **Monthly Budget Left:** Dynamic balance remaining from allocated budget.

6. **Total Expense:** Lifetime cumulative spend from system start date.

7. **Pending Reimbursements (Alert Card):** High-visibility card highlighting total pending Rupees owed to Younus and un-settled partner balance.

### 4.3 Expense List & Table View

- Tabular list showing recorded expenses with sortable columns:
    - Date

    - Paid By (Usman / Bilal / Younus)

    - Category

    - Description/Notes

    - Amount (in Rupees)

    - Reimbursement Status (`Pending` / `Cleared`)

    - Actions (Edit, Delete, Mark as Cleared)

### 4.4 Expense Management (CRUD & Modals)

- **No Page Redirections:** Add, Edit, and Delete operations handled via fast Modal Popups.

- **Inline Category Creation:**
    - Inside the "Add Expense" modal, a simple "+ Add Category" button opens a secondary modal.

    - Upon saving, the secondary modal closes and auto-selects the new category in the main form.

- **Searchable Dropdowns:** Quick search functionality inside category and payer selection dropdowns.

## 5. Summary of System Benefits for Project Manager

1. **Eliminates Forgetfulness:** Instant visual indication of whether Younus has been reimbursed or if Bilal has settled his balance.

2. **Zero Mental Math:** Calculates 50/50 partner split shares and net balances automatically.

3. **Operational Speed:** Minimal input fields required to log an expense.

4. **Complete Audit Trail:** Retains clear history for delayed reimbursements across multiple days.
