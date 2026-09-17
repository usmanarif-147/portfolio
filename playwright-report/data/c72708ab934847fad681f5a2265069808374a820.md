# Instructions

- Following Playwright test failed.
- Explain why, be concise, respect Playwright best practices.
- Provide a snippet of code with the fix, if possible.

# Test info

- Name: indus-gas.spec.ts >> complete Indus Gas business workflow
- Location: tests/e2e/indus-gas.spec.ts:5:1

# Error details

```
Error: expect(locator).toBeVisible() failed

Locator: getByText('Demo Indus Gas BBN Plant')
Expected: visible
Timeout: 5000ms
Error: element(s) not found

Call log:
  - Expect "toBeVisible" getByText('Demo Indus Gas BBN Plant') with timeout 5000ms
  - waiting for getByText('Demo Indus Gas BBN Plant')

```

```yaml
- complementary:
  - link "UA.":
    - /url: /
  - navigation:
    - link "Dashboard":
      - /url: http://localhost:8021/admin/dashboard
      - img
      - text: Dashboard
    - link "Contacts":
      - /url: http://localhost:8021/admin/contact
      - img
      - text: Contacts
    - link "Analytics":
      - /url: http://localhost:8021/admin/analytics
      - img
      - text: Analytics
    - button "Indus Gas":
      - img
      - text: Indus Gas
      - img
    - link "Dashboard":
      - /url: http://localhost:8021/admin/indus-gas/dashboard
    - link "Customers":
      - /url: http://localhost:8021/admin/indus-gas/customers
    - link "Daily Operations":
      - /url: http://localhost:8021/admin/indus-gas/daily-operations
    - link "Payments & Ledgers":
      - /url: http://localhost:8021/admin/indus-gas/payments-ledgers
    - link "Expenses":
      - /url: http://localhost:8021/admin/indus-gas/expenses
    - link "Reports & Planning":
      - /url: http://localhost:8021/admin/indus-gas/reports
    - link "Documents":
      - /url: http://localhost:8021/admin/indus-gas/documents
    - link "Settings":
      - /url: http://localhost:8021/admin/indus-gas/settings
    - button "Portfolio Manager":
      - img
      - text: Portfolio Manager
      - img
    - button "Social":
      - img
      - text: Social
      - img
    - link "Blogging":
      - /url: http://localhost:8021/admin/blog
      - img
      - text: Blogging
    - link "Blog & Post":
      - /url: http://localhost:8021/admin/blog-and-post
      - img
      - text: Blog & Post
    - button "Project Management":
      - img
      - text: Project Management
      - img
    - button "YouTube":
      - img
      - text: YouTube
      - img
    - button "Personal":
      - img
      - text: Personal
      - img
    - button "Settings":
      - img
      - text: Settings
      - img
  - text: I
  - paragraph: Indus Gas E2E Test
  - paragraph: indus-gas-e2e@example.test
  - button "Logout":
    - img
    - text: Logout
- main:
  - link "Suppliers":
    - /url: http://localhost:8021/admin/indus-gas/settings/suppliers
  - text: › Add
  - heading "Add Supplier" [level=1]
  - text: Supplier Name *
  - textbox "e.g. BBN Plant": Demo Indus Gas BBN Plant
  - paragraph: The name has already been taken.
  - text: Supply Type *
  - combobox:
    - option "LPG / Refilling" [selected]
    - option "Cylinders"
    - option "LPG and Cylinders"
  - text: Contact Person
  - textbox
  - text: Phone
  - textbox
  - text: Address / Notes
  - textbox
  - checkbox "Active supplier" [checked]
  - text: Active supplier
  - button "Save Supplier"
  - link "Cancel":
    - /url: http://localhost:8021/admin/indus-gas/settings/suppliers
```

# Test source

```ts
  1  | import { expect, test } from '@playwright/test';
  2  | 
  3  | const testId = 'Demo Indus Gas';
  4  | 
  5  | test('complete Indus Gas business workflow', async ({ page }) => {
  6  |     await page.goto('/admin/login');
  7  |     await page.locator('#email').fill('indus-gas-e2e@example.test');
  8  |     await page.locator('#password').fill('IndusGasE2E!2026');
  9  |     await page.getByRole('button', { name: 'Sign In' }).click();
  10 |     await expect(page).toHaveURL('http://localhost:8021/admin/dashboard');
  11 | 
  12 |     await page.goto('/admin/indus-gas/settings/suppliers/create');
  13 |     await page.locator('input[wire\\:model="name"]').fill(`${testId} BBN Plant`);
  14 |     await page.locator('select[wire\\:model="supply_type"]').selectOption('lpg');
  15 |     await page.getByRole('button', { name: 'Save Supplier' }).click();
> 16 |     await expect(page.getByText(`${testId} BBN Plant`)).toBeVisible();
     |                                                         ^ Error: expect(locator).toBeVisible() failed
  17 | 
  18 |     await page.goto('/admin/indus-gas/settings/cylinder-types/create');
  19 |     await page.locator('input[wire\\:model="name"]').fill(`${testId} Commercial`);
  20 |     await page.locator('input[wire\\:model="capacity_kg"]').fill('45');
  21 |     await page.getByRole('button', { name: 'Save Cylinder Type' }).click();
  22 |     await expect(page.getByText(`${testId} Commercial`)).toBeVisible();
  23 | 
  24 |     await page.goto('/admin/indus-gas/settings/vehicles/create');
  25 |     await page.locator('input[wire\\:model="name"]').fill(`${testId} Pickup`);
  26 |     await page.locator('input[wire\\:model="registration_number"]').fill('E2E-2026');
  27 |     await page.getByRole('button', { name: 'Save Vehicle' }).click();
  28 | 
  29 |     await page.goto('/admin/indus-gas/settings/expense-categories/create');
  30 |     await page.locator('input[wire\\:model="name"]').fill(`${testId} Fuel`);
  31 |     await page.getByRole('button', { name: 'Save Category' }).click();
  32 | 
  33 |     await page.goto('/admin/indus-gas/customers/create');
  34 |     await page.locator('#title').fill(`${testId} Restaurant`);
  35 |     await page.locator('#location').fill('Gulberg, Lahore');
  36 |     await page.locator('#phone').fill('03001234567');
  37 |     await page.locator('select[wire\\:model\\.live="payment_term"]').selectOption('weekly');
  38 |     const allocation = page.locator('input[wire\\:model*="allocations"]');
  39 |     await allocation.last().fill('5');
  40 |     await page.getByRole('button', { name: 'Save Customer' }).click();
  41 |     await expect(page.getByText(`${testId} Restaurant`)).toBeVisible();
  42 | 
  43 |     await page.goto('/admin/indus-gas/daily-operations/stock');
  44 |     await page.locator('select[wire\\:model="cylinder_type_id"]').selectOption({ label: `${testId} Commercial` });
  45 |     await page.locator('input[wire\\:model="quantity_change"]').fill('12');
  46 |     await page.locator('input[wire\\:model="reason"]').fill('E2E opening full stock');
  47 |     await page.getByRole('button', { name: 'Save Adjustment' }).click();
  48 |     await expect(page.getByText('Stock adjustment recorded.')).toBeVisible();
  49 |     await page.reload();
  50 |     await page.locator('select[wire\\:model="cylinder_type_id"]').selectOption({ label: `${testId} Commercial` });
  51 |     await page.locator('select[wire\\:model="stock_state"]').selectOption('empty');
  52 |     await page.locator('input[wire\\:model="quantity_change"]').fill('12');
  53 |     await page.locator('input[wire\\:model="reason"]').fill('E2E opening empty stock');
  54 |     await page.getByRole('button', { name: 'Save Adjustment' }).click();
  55 |     await expect(page.getByText('Stock adjustment recorded.')).toBeVisible();
  56 | 
  57 |     await page.goto('/admin/indus-gas/daily-operations/refills/create');
  58 |     await page.locator('select[wire\\:model="supplier_id"]').selectOption({ label: `${testId} BBN Plant` });
  59 |     await page.locator('input[wire\\:model="lpg_rate_per_kg"]').fill('280');
  60 |     await page.locator('input[wire\\:model="filled_kg"]').fill('225');
  61 |     await page.locator('input[wire\\:model*="quantities"]').last().fill('5');
  62 |     await page.getByRole('button', { name: 'Save Refill' }).click();
  63 |     await expect(page).toHaveURL(/admin\/indus-gas\/daily-operations$/);
  64 | 
  65 |     await page.goto('/admin/indus-gas/daily-operations/deliveries/create');
  66 |     await page.locator('select[wire\\:model\\.live="customer_id"]').selectOption({ label: `${testId} Restaurant` });
  67 |     await page.locator('input[wire\\:model="sale_rate_per_kg"]').fill('420');
  68 |     await page.locator('input[wire\\:model*="delivered"]').last().fill('3');
  69 |     await page.getByRole('button', { name: 'Save Delivery' }).click();
  70 |     await expect(page).toHaveURL(/admin\/indus-gas\/daily-operations$/);
  71 | 
  72 |     await page.goto('/admin/indus-gas/payments-ledgers');
  73 |     await page.getByRole('button', { name: 'Generate Invoice' }).click();
  74 |     await expect(page.getByText(`${testId} Restaurant`)).toBeVisible();
  75 |     await expect(page.getByText('PDF')).toBeVisible();
  76 | 
  77 |     await page.goto('/admin/indus-gas/payments-ledgers/payments/create');
  78 |     await page.locator('select[wire\\:model="customer_id"]').selectOption({ label: `${testId} Restaurant` });
  79 |     await page.locator('input[wire\\:model="amount"]').fill('30000');
  80 |     await page.locator('input[wire\\:model="reference"]').fill('E2E-ONLINE-001');
  81 |     await page.getByRole('button', { name: 'Save Payment' }).click();
  82 |     await expect(page.getByText('Payment recorded successfully')).toBeVisible();
  83 | 
  84 |     await page.goto('/admin/indus-gas/expenses/create');
  85 |     await page.locator('select[wire\\:model="expense_category_id"]').selectOption({ label: `${testId} Fuel` });
  86 |     await page.locator('input[wire\\:model="amount"]').fill('4500');
  87 |     await page.locator('input[wire\\:model="payee"]').fill('PSO');
  88 |     await page.getByRole('button', { name: 'Save Expense' }).click();
  89 |     await expect(page.getByText('Expense recorded successfully')).toBeVisible();
  90 | 
  91 |     await page.goto('/admin/indus-gas/reports');
  92 |     await expect(page.getByText('Reports & Planning')).toBeVisible();
  93 |     await expect(page.getByText('LPG Rate History')).toBeVisible();
  94 |     await page.screenshot({ path: 'test-results/indus-gas-e2e-dashboard.png', fullPage: true });
  95 | });
  96 | 
```