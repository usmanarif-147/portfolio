import { expect, test } from '@playwright/test';

const testId = 'Demo Indus Gas';

test('complete Indus Gas business workflow', async ({ page }) => {
    await page.goto('/admin/login');
    await page.locator('#email').fill('indus-gas-e2e@example.test');
    await page.locator('#password').fill('IndusGasE2E!2026');
    await page.getByRole('button', { name: 'Sign In' }).click();
    await expect(page).toHaveURL('http://localhost:8021/admin/dashboard');

    await page.goto('/admin/indus-gas/settings/suppliers/create');
    await page.locator('input[wire\\:model="name"]').fill(`${testId} BBN Plant`);
    await page.locator('select[wire\\:model="supply_type"]').selectOption('lpg');
    await page.getByRole('button', { name: 'Save Supplier' }).click();
    await expect(page.getByText(`${testId} BBN Plant`)).toBeVisible();

    await page.goto('/admin/indus-gas/settings/cylinder-types/create');
    await page.locator('input[wire\\:model="name"]').fill(`${testId} Commercial`);
    await page.locator('input[wire\\:model="capacity_kg"]').fill('45');
    await page.getByRole('button', { name: 'Save Cylinder Type' }).click();
    await expect(page.getByText(`${testId} Commercial`)).toBeVisible();

    await page.goto('/admin/indus-gas/settings/vehicles/create');
    await page.locator('input[wire\\:model="name"]').fill(`${testId} Pickup`);
    await page.locator('input[wire\\:model="registration_number"]').fill('E2E-2026');
    await page.getByRole('button', { name: 'Save Vehicle' }).click();

    await page.goto('/admin/indus-gas/settings/expense-categories/create');
    await page.locator('input[wire\\:model="name"]').fill(`${testId} Fuel`);
    await page.getByRole('button', { name: 'Save Category' }).click();

    await page.goto('/admin/indus-gas/customers/create');
    await page.locator('#title').fill(`${testId} Restaurant`);
    await page.locator('#location').fill('Gulberg, Lahore');
    await page.locator('#phone').fill('03001234567');
    await page.locator('select[wire\\:model\\.live="payment_term"]').selectOption('weekly');
    const allocation = page.locator('input[wire\\:model*="allocations"]');
    await allocation.last().fill('5');
    await page.getByRole('button', { name: 'Save Customer' }).click();
    await expect(page.getByText(`${testId} Restaurant`)).toBeVisible();

    await page.goto('/admin/indus-gas/daily-operations/stock');
    await page.locator('select[wire\\:model="cylinder_type_id"]').selectOption({ label: `${testId} Commercial` });
    await page.locator('input[wire\\:model="quantity_change"]').fill('12');
    await page.locator('input[wire\\:model="reason"]').fill('E2E opening full stock');
    await page.getByRole('button', { name: 'Save Adjustment' }).click();
    await expect(page.getByText('Stock adjustment recorded.')).toBeVisible();
    await page.reload();
    await page.locator('select[wire\\:model="cylinder_type_id"]').selectOption({ label: `${testId} Commercial` });
    await page.locator('select[wire\\:model="stock_state"]').selectOption('empty');
    await page.locator('input[wire\\:model="quantity_change"]').fill('12');
    await page.locator('input[wire\\:model="reason"]').fill('E2E opening empty stock');
    await page.getByRole('button', { name: 'Save Adjustment' }).click();
    await expect(page.getByText('Stock adjustment recorded.')).toBeVisible();

    await page.goto('/admin/indus-gas/daily-operations/refills/create');
    await page.locator('select[wire\\:model="supplier_id"]').selectOption({ label: `${testId} BBN Plant` });
    await page.locator('input[wire\\:model="lpg_rate_per_kg"]').fill('280');
    await page.locator('input[wire\\:model="filled_kg"]').fill('225');
    await page.locator('input[wire\\:model*="quantities"]').last().fill('5');
    await page.getByRole('button', { name: 'Save Refill' }).click();
    await expect(page).toHaveURL(/admin\/indus-gas\/daily-operations$/);

    await page.goto('/admin/indus-gas/daily-operations/deliveries/create');
    await page.locator('select[wire\\:model\\.live="customer_id"]').selectOption({ label: `${testId} Restaurant` });
    await page.locator('input[wire\\:model="sale_rate_per_kg"]').fill('420');
    await page.locator('input[wire\\:model*="delivered"]').last().fill('3');
    await page.getByRole('button', { name: 'Save Delivery' }).click();
    await expect(page).toHaveURL(/admin\/indus-gas\/daily-operations$/);

    await page.goto('/admin/indus-gas/payments-ledgers');
    await page.getByRole('button', { name: 'Generate Invoice' }).click();
    await expect(page.getByText(`${testId} Restaurant`)).toBeVisible();
    await expect(page.getByText('PDF')).toBeVisible();

    await page.goto('/admin/indus-gas/payments-ledgers/payments/create');
    await page.locator('select[wire\\:model="customer_id"]').selectOption({ label: `${testId} Restaurant` });
    await page.locator('input[wire\\:model="amount"]').fill('30000');
    await page.locator('input[wire\\:model="reference"]').fill('E2E-ONLINE-001');
    await page.getByRole('button', { name: 'Save Payment' }).click();
    await expect(page.getByText('Payment recorded successfully')).toBeVisible();

    await page.goto('/admin/indus-gas/expenses/create');
    await page.locator('select[wire\\:model="expense_category_id"]').selectOption({ label: `${testId} Fuel` });
    await page.locator('input[wire\\:model="amount"]').fill('4500');
    await page.locator('input[wire\\:model="payee"]').fill('PSO');
    await page.getByRole('button', { name: 'Save Expense' }).click();
    await expect(page.getByText('Expense recorded successfully')).toBeVisible();

    await page.goto('/admin/indus-gas/reports');
    await expect(page.getByText('Reports & Planning')).toBeVisible();
    await expect(page.getByText('LPG Rate History')).toBeVisible();
    await page.screenshot({ path: 'test-results/indus-gas-e2e-dashboard.png', fullPage: true });
});
