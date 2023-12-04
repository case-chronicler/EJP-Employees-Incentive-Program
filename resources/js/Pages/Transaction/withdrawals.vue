<script setup>
import axios from "axios";

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Dropdown from "@/Components/Dropdown.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import RequestFunds from "@/Modals/RequestFunds.vue";

import Coin from "@/Components/icons/coin.vue";
import User from "@/Components/icons/user.vue";

import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { computed, reactive, ref } from "vue";

import { useModalStore } from "@/Store/modal";

const modalStore = useModalStore();

const props = defineProps({
	allWithdrawals: Object,
});

const isEmployeeAnAttorney = usePage().props.isEmployeeAnAttorney;

const gift_type = ref("");
const user = ref([]);

const requestsResult = reactive({
	addRole: null,
});

const processed_users_and_employees = computed(() => {
	const processed_users_and_employees = props.users_and_employees;
	if (requestsResult.addRole === null) {
		return processed_users_and_employees;
	} else {
		for (const key in requestsResult.addRole) {
			if (Object.hasOwnProperty.call(requestsResult.addRole, key)) {
				const element = requestsResult.addRole[key];

				processed_users_and_employees[key] = element;
			}
		}

		return processed_users_and_employees;
	}
});

const addRole = async (user_id, position_id) => {
	try {
		let res = await axios.post(route("employee.store"), {
			user_id: user_id,
			position_id: position_id,
		});

		const processed_users_and_employees =
			res?.data?.processed_users_and_employees;
		requestsResult.addRole = processed_users_and_employees;
	} catch (error) {}
};

const get_allWithdrawals = computed(() => {
	return props.allWithdrawals?.data ?? [];
});

const get_nextPageData = computed(() => {
	let nextPageData = props.allWithdrawals?.links ?? false;

	if (nextPageData) {
		nextPageData = nextPageData.find((elem) => {
			return elem.label == "Next &raquo;";
		});
	}

	return nextPageData;
});

const get_prevPageData = computed(() => {
	let prevPageData = props.allWithdrawals?.links ?? false;

	if (prevPageData) {
		prevPageData = prevPageData.find((elem) => {
			return elem.label == "&laquo; Previous";
		});
	}

	return prevPageData;
});
</script>

<style>
body {
	--tw-bg-opacity: 1 !important;
	background-color: rgb(243 244 246 / var(--tw-bg-opacity)) !important;
}
</style>

<template>
	<RequestFunds v-if="modalStore.currentModal === 'REQUEST_FUNDS'" />

	<Head title="Withdrawals" />

	<AuthenticatedLayout>
		<template #header>
			<div class="flex items-center space-x-4">
				<h2 class="font-semibold text-xl text-gray-800 leading-tight">
					Withdrawals
				</h2>
			</div>
		</template>
		<div class="my-16">
			<div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-14">
				<div class="mb-4" v-if="!isEmployeeAnAttorney">
					<PrimaryButton
						@click.prevent="modalStore.openRequestFundsModal()"
						type="button"
						class="mx-2 float-right"
						>request funds</PrimaryButton
					>
					<div class="clear-both"></div>
				</div>

				<div class="rounded-lg">
					<template v-if="get_allWithdrawals.length > 0">
						<div class="grid grid-cols-1 gap-4">
							<div
								class="flex items-center justify-center mb-4"
								v-for="(withdrawal, index) in get_allWithdrawals"
								:key="index"
							>
								<div class="rounded-xl border p-5 shadow-md w-full bg-white">
									<div class="flex w-full items-center justify-between mb-6">
										<div class="flex items-center space-x-3">
											<div
												class="h-8 w-8 rounded-full flex items-center justify-center shadow-lg"
											>
												<User w_class="w-5" h_class="h-5" />
											</div>
											<div class="text-lg font-bold text-slate-700">
												{{ withdrawal.user_email }}
											</div>
										</div>
										<div class="flex items-center justify-center space-x-4">
											<div
												class="text-base text-neutral-500 inline-flex items-center"
											>
												<Coin class="mr-1" />
												{{ withdrawal.withdrawal_request_amount }}
											</div>
											<div
												class="rounded-2xl border bg-neutral-100 px-2 py-1 text-xs font-semibold uppercase"
												:class="{
													'text-amber-500':
														withdrawal.withdrawal_request_status == 'pending',
													'text-red-700':
														withdrawal.withdrawal_request_status == 'failed' ||
														withdrawal.withdrawal_request_status == 'rejected',
													'text-green-700':
														withdrawal.withdrawal_request_status == 'success',
												}"
											>
												{{ withdrawal.withdrawal_request_status }}
											</div>
										</div>
									</div>

									<div>
										<div
											class="flex items-center justify-between text-slate-500"
										>
											<div class="flex space-x-4 md:space-x-8">
												<Link
													:href="
														route(
															'withdrawal_requests.show',
															withdrawal.withdrawal_request_link_id
														)
													"
													class="flex cursor-pointer items-center transition hover:text-slate-600"
												>
													<span
														class="middle none center rounded-lg border border-cyan-800 py-1.5 px-3 font-sans text-xs font-bold uppercase text-cyan-500 transition-all hover:opacity-75 focus:ring focus:ring-cyan-200 active:opacity-[0.85] disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
														data-ripple-dark="true"
													>
														View transaction
													</span>
												</Link>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="my-8">
							<div class="flex float-right">
								<!-- Previous Button -->
								<a
									:href="get_prevPageData?.url ?? '#'"
									class="flex items-center justify-center px-4 h-10 text-base font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
								>
									Previous
								</a>

								<!-- Next Button -->
								<a
									:href="get_nextPageData?.url ?? '#'"
									class="flex items-center justify-center px-4 h-10 ms-3 text-base font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
								>
									Next
								</a>
							</div>
							<div class="clear-both"></div>
						</div>
					</template>
				</div>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
