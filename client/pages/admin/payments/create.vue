<template>
    <div>
        <NuxtLayout name="admin">
            <main class="w-full mx-auto">

                <Head>
                    <Title>Bills Payment - {{ runtimeConfig.public.appName }}</Title>
                </Head>
                <div>
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t border-gray-300" />
                        </div>
                        <div class="relative flex">
                            <h2 class="bg-white text-lg font-semibold">BILLS PAYMENT</h2>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4 mt-8">
                            <div class="w-1/2">
                                <FormLabel label="Supplier" />
                                <FormSelect id="supplier_id" v-model="state.selectedSupplierId"
                                    :options="state.suppliers.map(s => ({ value: s.id, label: s.name }))"
                                    placeholder="select supplier" required />
                            </div>
                            <div class="w-1/2">
                                <FormLabel label="Is Cancelled" />
                                <FormSelect v-model="payment.is_cancelled" :options="[
                                    { value: false, label: 'No' },
                                    { value: true, label: 'Yes' },
                                ]">
                                </FormSelect>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 mt-8">
                            <div class="w-1/4">
                                <FormLabel label="Payment Type" />
                                <FormSelect v-model="payment.payment_type" :options="[
                                    { value: 'cash', label: 'Cash' },
                                    { value: 'cheque', label: 'Cheque' },
                                    { value: 'gcash', label: 'Gcash' },
                                ]">
                                </FormSelect>
                            </div>
                            <div class="w-1/4">
                                <FormLabel label="Cash Voucher Number" />
                                <FormTextField id="cashvoucher" name="cashvoucher" v-model="makepayment.cash_voucher_no"
                                    placeholder="voucher number" />
                            </div>
                            <div class="w-1/4">
                                <FormLabel label="Date" />
                                <input id="date" type="date" v-model="makepayment.date"
                                    class="block w-full rounded-md border border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 text-sm px-3 py-2"
                                    required />
                            </div>
                            <div class="w-1/4">
                                <FormLabel label="Prepared By" />
                                <FormNumberField for="prepared_by_id" name="prepared_by_id"
                                    :placeholder="`${firstname} ${lastname}`" :value="`${firstname} ${lastname}`"
                                    readonly class="block cursor-default bg-gray-200" />
                            </div>
                        </div>
                        <div v-if="state.selectedSupplierId" class="relative">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="w-full border-t border-gray-300" />
                            </div>
                            <div class="relative flex">
                                <h2 class="bg-white text-lg font-semibold">BILLS DETAILS</h2>
                            </div>
                        </div>
                        <div v-if="state.selectedSupplierId" class="flex mt-8">
                            <div class="w-full">
                                <div class="relative">
                                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                        <div class="w-full border-t border-gray-300" />
                                    </div>
                                    <div class="relative flex">
                                        <h2 class="bg-white text-lg font-normal">Unpaid Bills</h2>
                                    </div>
                                </div>
                                <!-- Product Table -->
                                <Alert type="danger" :text="state.error?.message" v-if="state.error" />
                                <div class="table-responsive">
                                    <Table :columnHeaders="state.unpaidBillColumnHeader" :data="state.unpaidBills"
                                        :isLoading="state.isTableLoading" :sortData="state.sortData" @sort="sort">
                                        <template #body v-if="!state.isTableLoading && state.unpaidBills?.data.length">
                                            <tr v-for="(bill, index) in state.unpaidBills?.data" :key="index">
                                                <td class="pl-3">
                                                    {{ bill.id }}
                                                </td>
                                                <td class="pl-3">
                                                    {{ bill.purchase_order_no }}
                                                </td>
                                                <td class="pl-3">
                                                    {{ bill.bill_date }}
                                                </td>
                                                <td class="pl-3">
                                                    {{ bill.amount }}
                                                </td>
                                                <td class="pl-3">
                                                    <FormButton button-style="warning" @click="selectBill(bill)">Select
                                                    </FormButton>
                                                </td>
                                            </tr>
                                        </template>
                                    </Table>
                                </div>
                                <!-- <Pagination :data="state.categories" @previous="previous" @next="next" /> -->
                            </div>
                        </div>
                        <div v-if="state.selectedSupplierId" class="flex space-x-4 mt-8">
                            <div class="w-1/2">
                                <FormLabel label="Bill ID" />
                                <FormNumberField id="billid" name="Bill ID" v-model="selectedbillinput.bill_id"
                                    placeholder="Bill ID" />
                                <FormLabel label="Purchase Order Number" />
                                <FormTextField id="ponumber" name="ponumber"
                                    v-model="selectedbillinput.purchase_order_no" placeholder="Purchase Order Number" />
                                <FormLabel label="Date" />
                                <FormTextField id="date" name="date" v-model="selectedbillinput.bill_date"
                                    placeholder="Date" />
                                <FormLabel label="amount" />
                                <FormNumberField id="amount" name="amount" v-model="selectedbillinput.amount"
                                    placeholder="Amount" />
                                <FormLabel label="Payment Amount" />
                                <FormNumberField id="paymentamount" name="paymentamount"
                                    v-model="selectedbillinput.amount_to_pay" placeholder="Payment Amount" />
                                <FormButton class="mt-3 w-full" button-style="success" @click="addBillsPaymentDetail">
                                    Add Bills
                                    Payment
                                    Detail
                                </FormButton>
                            </div>
                            <div class="w-1/2">
                                <FormButton v-if="payment.payment_type === 'cheque'" button-style="xxx">Add Cheque
                                </FormButton>
                                <FormLabel v-if="payment.payment_type === 'cheque'" label="Cheque Amount" />
                                <FormNumberField v-if="payment.payment_type === 'cheque'" v-model="payment.cheque"
                                    name="chequeamount" placeholder="Cheque Amount" />
                                <FormLabel v-if="payment.payment_type === 'gcash'" label="Gcash Amount" />
                                <FormNumberField v-if="payment.payment_type === 'gcash'" v-model="payment.gcash"
                                    name="gcashamount" placeholder="Gcash Amount" />
                                <FormLabel v-if="payment.payment_type === 'gcash'" label="Gcash Reference Number" />
                                <FormNumberField v-if="payment.payment_type === 'gcash'"
                                    v-model="payment.gcashreference" name="gcashreference"
                                    placeholder="Gcash Reference Number" />
                                <FormLabel label="Cash Amount" />
                                <FormNumberField v-model="payment.cash" name="cashamount" placeholder="Cash Amount" />
                                <FormLabel label="Total Amount" />
                                <FormNumberField v-model="formattedPaymentAmount" name="totalamount"
                                    placeholder="Total Amount" readonly />
                                <FormLabel label="Total Payment Amount" />
                                <FormNumberField v-model="formattedTotalAmountToPay" name="totalamount"
                                    placeholder="Amount to Pay" readonly />
                            </div>
                        </div>
                        <div class="flex space-x-4 mt-8">
                            <div v-if="state.selectedSupplierId" class="w-full">
                                <div class="relative">
                                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                        <div class="w-full border-t border-gray-300" />
                                    </div>
                                    <div class="relative flex">
                                        <h2 class="bg-white text-lg font-normal">Cheque Details</h2>
                                    </div>
                                </div>
                                <!-- Product table -->
                                <Alert type="danger" :text="state.error?.message" v-if="state.error" />
                                <div class="table-responsive">
                                    <Table :columnHeaders="state.chequeDetailColumnHeader" :data="state.cheques"
                                        :isLoading="state.isTableLoading" :sortData="state.sortData" @sort="sort">
                                        <template #body v-if="!state.isTableLoading && state.cheques?.data.length">
                                            <tr v-for="(cheque, index) in state.cheques?.data" :key="index">
                                                <td class="pl-3">
                                                    {{ cheque.id }}
                                                </td>
                                                <td class="pl-3">
                                                    {{ cheque.purchase_order_no }}
                                                </td>
                                                <td class="pl-3">
                                                    {{ cheque.bill_date }}
                                                </td>
                                            </tr>
                                        </template>
                                    </Table>
                                </div>
                                <!-- <Pagination :data="state.categories" @previous="previous" @next="next" /> -->
                            </div>
                            <div class="w-full">
                                <div class="relative">
                                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                        <div class="w-full border-t border-gray-300" />
                                    </div>
                                    <div class="relative flex">
                                        <h2 class="bg-white text-lg font-normal">Selected Bills</h2>
                                    </div>
                                </div>
                                <!-- Product table -->
                                <Alert type="danger" :text="state.error?.message" v-if="state.error" />
                                <div class="table-responsive">
                                    <Table :columnHeaders="state.selectedBillColumnHeader" :data="state.selectedBills"
                                        :isLoading="state.isTableLoading" :sortData="state.sortData" @sort="sort">
                                        <template #body
                                            v-if="!state.isTableLoading && state.selectedBills?.data.length">
                                            <tr v-for="(bill, index) in state.selectedBills?.data" :key="index">
                                                <td class="pl-3">
                                                    {{ bill.id }}
                                                </td>
                                                <td class="pl-3">
                                                    {{ bill.purchase_order_no }}
                                                </td>
                                                <td class="pl-3">
                                                    {{ bill.bill_date }}
                                                </td>
                                                <td class="pl-3">
                                                    {{ bill.amount }}
                                                </td>
                                                <td class="pl-3">
                                                    {{ bill.paymentamount }}
                                                </td>
                                            </tr>
                                        </template>
                                    </Table>
                                </div>
                                <!-- <Pagination :data="state.categories" @previous="previous" @next="next" /> -->
                            </div>
                        </div>
                        <div v-if="state.selectedSupplierId" class="flex space-x-4 mt-8">

                        </div>
                        <div v-if="isPaymentAmountValid" class="flex space-x-4 mt-8">
                            <div class="w-full">
                                <FormButton class="w-full" button-style="success" @click="makePayment">Make Payment
                                </FormButton>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </NuxtLayout>
    </div>
</template>

<script setup lang="ts">
import { reactive, onMounted } from 'vue';
import type { Error } from '@/types/error';
import { supplierService } from '~/components/api/admin/SupplierService';
import { billService } from '~/components/api/admin/BillService';
import { useI18n } from 'vue-i18n';
import { billPaymentService } from '~/components/api/admin/BillPaymentService';
import { billPaymentDetailService } from '~/components/api/admin/BillPaymentDetailService';

// Alert and i18n setup
const { successAlert } = useAlert();
const { errorAlert } = useAlert();
const { warningAlert } = useAlert();
const { t } = useI18n()

const user_id = computed(() => localStorage.getItem('user_id'));
const firstname = computed(() => localStorage.getItem('firstname'));
const lastname = computed(() => localStorage.getItem('lastname'));
const runtimeConfig = useRuntimeConfig();

let currentTablePage = 1;

interface UnpaidBill {
    data: any[];
}

interface SelectedBill {
    data: any[];
}

interface Cheques {
    data: any[];
}

interface SortData {
    sortField: string;
    sortOrder: "ascend" | "descend" | null;
}

function sort(sortingData: { column: string; sort: string }) {
    currentTablePage = 1;
    if (sortingData.sort === 'ascend' || sortingData.sort === 'descend') {
        state.sortData = {
            sortField: sortingData.column,
            sortOrder: sortingData.sort,
        };
    } else {
        console.error('Invalid sort order:', sortingData.sort);
        state.sortData = {
            sortField: sortingData.column,
            sortOrder: 'ascend',
        };
    }
    fetchBills();
}

// Computed property to check if payment amount is valid
const isPaymentAmountValid = computed(() => {
    return paymentAmountNumber.value > 0;
});

const totalAmountToPay = computed(() => {
    return state.selectedBills?.data.reduce((total, bill) => total + Number(bill.paymentamount), 0);
});

const formattedTotalAmountToPay = computed(() => {
    return totalAmountToPay.value.toFixed(2);
});

const formattedPaymentAmount = computed({
    get() {
        return paymentAmountNumber.value.toString();
    },
    set(value) {
        paymentAmountNumber.value = Number(value);
    }
});

const paymentAmountNumber = computed({
    get() {
        return Number(payment.gcash) + Number(payment.cheque) + Number(payment.cash);
    },
    set(value) {
        payment.amount = value.toString();
    },
});

async function fetchBills() {
    state.error = null;
    state.isTableLoading = true;

    try {
        // Fetch all bills
        const response = await billService.getBills();
        console.log("Fetched bills:", response.data);  // Debug: Log all fetched bills

        // Filter bills by selected supplier ID if it exists
        if (state.selectedSupplierId) {
            const filteredBills = response.data.filter((bill: any) => {
                console.log("Checking bill supplier:", bill.supplier_id, "against", state.selectedSupplierId);
                return String(bill.supplier_id) === String(state.selectedSupplierId);
            });

            state.unpaidBills = { data: filteredBills };
        } else {
            // If no supplier is selected, show all bills
            state.unpaidBills = response;
        }

    } catch (error: any) {
        state.error = error;
    }

    state.isTableLoading = false;
}

interface Supplier {
    id: string;
    name: string;
    email: string;
    phone: string;
    address: string;
    is_active: boolean;
}

interface amountToPay {
    amount: string;
}

const supplier = reactive({
    id: '',
    name: '',
    email: '',
    phone: '',
    address: '',
    is_active: true,
});

interface Payment {
    supplier_id: string;
    payment_type: string;
    voucher_number: string;
    date: string;
    amount: string;
    is_cancelled: boolean;
}

const payment = reactive({
    supplier_id: '',
    payment_type: 'cash',
    voucher_number: '',
    date: '',
    gcash: '',
    gcashreference: '',
    cheque: '',
    cash: '',
    amount: '',
    is_cancelled: false,
});

interface MakePayment {
    supplier_id: number;
    prepared_by_id: number;
    approved_by_id: number;
    cancelled_by_id: number;
    payment_type: string;
    cash_voucher_no: string;
    date: string;
    is_cancelled: boolean;
}

interface PaySelectedBillDetail {
    bills_payment_id: number;
    purchase_order_number: string;
    date: string;
    amount: string;
    amount_to_pay: string;
}

const paySelectedBills = reactive<PaySelectedBillDetail[]>([]);

const makepayment = reactive({
    supplier_id: user_id.value,
    prepared_by_id: user_id.value,
    approved_by_id: '',
    cancelled_by_id: '',
    payment_type: payment.payment_type,
    cash_voucher_no: payment.voucher_number,
    date: payment.date,
    is_cancelled: payment.is_cancelled,
});

interface selectedBillInput {
    bill_id: string;
    purchase_order_no: string;
    bill_date: string;
    amount: string;
    amount_to_pay: string;
}

const selectedbillinput = reactive({
    bill_id: '',
    purchase_order_no: '',
    bill_date: '',
    amount: '',
    amount_to_pay: '',
});

const state = reactive({
    unpaidBillColumnHeader: [
        { name: "ID", sorter: true, key: "id" },
        { name: "P.O. Number", sorter: true, key: "poNumber" },
        { name: "Date", sorter: true, key: "date" },
        { name: "Balance", sorter: true, key: "amount" },
        { name: "Actions", sorter: true, key: "actions" },
    ],
    selectedBillColumnHeader: [
        { name: "ID", sorter: true, key: "id" },
        { name: "P.O. Number", sorter: true, key: "poNumber" },
        { name: "Date", sorter: true, key: "date" },
        { name: "Amount", sorter: true, key: "amount" },
        { name: "Payment Amount", sorter: true, key: "paymentamount" },
    ],
    chequeDetailColumnHeader: [
        { name: "Cheque Number", sorter: true, key: "chequeNumber" },
        { name: "Cheque Date", sorter: true, key: "chequeDate" },
        { name: "Amount", sorter: true, key: "amount" },
    ],
    isTableLoading: false,
    error: null as Error | null,
    sortData: { sortField: 'id', sortOrder: 'ascend' } as SortData,
    unpaidBills: { data: [] } as UnpaidBill,
    selectedBills: { data: [] } as SelectedBill,
    cheques: { data: [] } as Cheques,
    suppliers: [] as Supplier[],
    payments: [] as Payment[],
    makepaymentsData: [] as MakePayment[],
    selectedSupplierId: null as number | null,
    paySelectedBills: [] as PaySelectedBillDetail[],
});

async function fetchSuppliers() {
    try {
        const response = await supplierService.getSuppliers();
        console.log(response);  // Log to see if the data structure matches expectations
        state.suppliers = response.data.filter((supplier: Supplier) => supplier.is_active);
    } catch (error) {
        console.error(error);
    }
}

function selectBill(bill: Bill) {
    const selectedBill = state.unpaidBills.data.find((b) => b.id === bill.id);
    if (selectedBill) {
        // Populate selectedbillinput with the selected bill's data
        selectedbillinput.bill_id = selectedBill.id;
        selectedbillinput.purchase_order_no = selectedBill.purchase_order_no;
        selectedbillinput.bill_date = selectedBill.bill_date;
        selectedbillinput.amount = selectedBill.amount;
    }
}
function addBillsPaymentDetail() {
    console.log("selected bill amount to pay input: ", selectedbillinput.amount_to_pay);
    console.log("selected bill amount input: ", selectedbillinput.amount);
    if (Number(selectedbillinput.amount_to_pay) > Number(selectedbillinput.amount)) {
        errorAlert('Error', 'Payment amount cannot be greater than the bill amount');
        return; // Early return to prevent adding the bill
    }

    const newBill: Bill = {
        id: selectedbillinput.bill_id,
        purchase_order_no: selectedbillinput.purchase_order_no,
        bill_date: selectedbillinput.bill_date,
        amount: selectedbillinput.amount,
        paymentamount: selectedbillinput.amount_to_pay,
    };

    state.selectedBills.data.push(newBill);

    // Optionally clear the form
    selectedbillinput.bill_id = '';
    selectedbillinput.purchase_order_no = '';
    selectedbillinput.bill_date = '';
    selectedbillinput.amount = '';
    selectedbillinput.amount_to_pay = '';
}


async function makePayment() {
    try {
        const paymentData = {
            prepared_by_id: user_id.value,
            approved_by_id: makepayment.approved_by_id,
            cancelled_by_id: makepayment.cancelled_by_id,
            payment_date: makepayment.date,
            payment_type: makepayment.payment_type,
            cash_voucher_no: makepayment.cash_voucher_no,
            is_cancelled: payment.is_cancelled,
        };

        console.log('Payment Data', paymentData);
        console.log('Selected Bills Data:', state.selectedBills.data);
        state.selectedBills.data.forEach(bill => {
            console.log('BILL ID FROM SELECTED BILLS:', bill.id);
        });
        console.log('Payment Amount:', formattedPaymentAmount.value);

        //Create new bill.
        const response = await billPaymentService.createBillPayment(paymentData);

        if (response && response.data.id) {
            console.log(response);

            // Save bill payment details
            for (const detail of state.selectedBills.data) {
                const billPaymentDetail = {
                    bill_id: detail.id,
                    bills_payment_id: response.data.id,
                    amount: detail.paymentamount,
                };

                console.log('Saving bill payment detail:', billPaymentDetail); // Log the detail being saved
                const result = await billPaymentDetailService.createBillPaymentDetail(billPaymentDetail);

                if (result) {
                    console.log('Bill Payment detail saved successfully:', result);
                } else {
                    console.error('Failed to save bill payment detail:', billPaymentDetail);
                }
            }
            successAlert(t('alert.bill_created'), t('alert.success'));
        } else {
            errorAlert(t('Error'), t('Failed to create bill.'));
        }

        if (makepayment.payment_type === 'cheque') {
            console.log('inserting cheque details to db');
        }
        else {
            console.log('cash payment no need to save cheque details.');
        }
        navigateTo('/admin/payments');
    }
    catch (error: any) {
        console.error('Error saving bill:', error.message);
        errorAlert(t('Error'), t('An error occurred while saving the bill.'));
    }
}

watch(() => payment.amount, (newAmount) => {
    console.log('Payment amount changed:', newAmount);
});

watch(
    () => state.selectedSupplierId,
    (newValue, oldValue) => {
        console.log("Supplier changed from", oldValue, "to", newValue);  // Debug: Log supplier change
        fetchBills();
        state.selectedBills.data = [];
    }
);

watch(
    () => supplier.id,
    (newSupplierID) => {
        // Find the selected product based on the selected product_id
        const selectedSupplier = state.suppliers.find(supplier => supplier.id === newSupplierID);

        // Update the name in billDetail if the selected product exists
        if (selectedSupplier) {
            supplier.id = selectedSupplier.id;
            supplier.name = selectedSupplier.name;
            supplier.email = selectedSupplier.email;
            supplier.phone = selectedSupplier.phone;
            supplier.address = selectedSupplier.address;
        } else {
            supplier.name = '';
            payment.payment_type = 'Cash';
            supplier.email = '';
            supplier.phone = '';
            supplier.address = '';
        }
    }
);

// Fetch suppliers when the component is mounted
onMounted(() => {
    fetchSuppliers();
    fetchBills();
});
</script>