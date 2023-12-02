<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, Link } from "@inertiajs/vue3";

import Coin from "@/Components/icons/coin.vue";

import EmployeeSvgIcon from "@/Components/icons/employee_icon.vue";
import GiftIcon from "@/Components/icons/gift.vue";
import SendGift from "@/Modals/SendGift.vue";

import incentiveListHandler from "@/Components/employee/incentives.vue";
import transactionsListHandler from "@/Components/employee/transactions.vue";

import { onMounted, onUpdated } from "vue";
import { initFlowbite } from "flowbite";

// initialize components based on data attribute selectors
onMounted(() => {
	initFlowbite();
});
onUpdated(() => {
	initFlowbite();
});

const props = defineProps({
	employeeData: Object,
});

const formatDate = (date) => {
	try {
		const options = { day: "numeric", month: "long", year: "numeric" };

		const today = new Date(date);
		const formattedDate = new Intl.DateTimeFormat("en-US", options).format(
			today
		);

		return formattedDate;
	} catch (error) {
		return "";
	}
};
</script>

<template>
	<Head title="Employee name" />

	<AuthenticatedLayout>
		<template #header>
			<div class="flex items-center space-x-4">
				<h2 class="font-semibold text-xl text-gray-800 leading-tight">
					{{ employeeData?.user_fullname ?? "" }}
				</h2>
			</div>
		</template>

		<div class="py-12 mx-3 md:mx-auto">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
				<div class="">
					<Link :href="route('employee.index')" class="float-right">
						<PrimaryButton type="button">All Employees</PrimaryButton>
					</Link>

					<div class="clear-both"></div>
				</div>
				<div>
					<div class="container mx-auto py-8">
						<div class="grid grid-cols-4 lg:grid-cols-12 gap-6">
							<div class="col-span-4 lg:col-span-4">
								<div class="bg-white shadow rounded-2xl p-6">
									<div class="flex flex-col items-center">
										<div class="h-18 w-18 border-2 rounded-full p-3 mb-4">
											<EmployeeSvgIcon />
										</div>

										<h1 class="text-xl font-bold">
											{{ employeeData?.user_fullname ?? "" }}
										</h1>
										<p class="text-gray-600">
											<span>
												{{ employeeData?.user_email ?? "" }}
											</span>
										</p>
										<p class="text-gray-600">
											<span class="inline-flex items-center mr-4">
												<Coin
													w_class="w-[16px]"
													h_class="h-[16px]"
													class="mr-1"
												/>
												{{ employeeData?.employee_bal ?? "" }}
											</span>
										</p>
										<div class="mt-6 flex flex-wrap gap-4 justify-center">
											<button
												type="button"
												class="px-3 py-1.5 text-xs font-medium text-center text-white bg-red-700 rounded hover:bg-red-800 focus:ring-2 focus:outline-none focus:ring-red-300 uppercase"
											>
												Put on probation
											</button>
											<button
												type="button"
												class="px-3 py-1.5 text-xs font-medium text-center text-white bg-green-700 rounded hover:bg-green-800 focus:ring-2 focus:outline-none focus:ring-green-300 uppercase"
											>
												Restore eligiblity
											</button>
										</div>
									</div>
									<hr class="my-6 border-t border-gray-300" />
									<div class="flex flex-col mb-3">
										<span
											class="text-gray-800 uppercase font-bold tracking-wider mb-1"
											>Status</span
										>
										<ul class="text-sm text-gray-600">
											<li class="mb-2">JavaScript</li>
										</ul>
									</div>
									<div class="flex flex-col mb-3">
										<span
											class="text-gray-800 uppercase font-bold tracking-wider mb-1"
											>Withdrawal Eligiblity</span
										>
										<ul class="text-sm text-gray-600">
											<li class="mb-2">JavaScript</li>
										</ul>
									</div>
									<div class="flex flex-col mb-3">
										<span
											class="text-gray-800 uppercase font-bold tracking-wider mb-1"
											>Roles</span
										>
										<ul class="text-sm text-gray-600">
											<li
												v-for="position in employeeData?.positions ?? []"
												:key="position"
												class="mb-2"
											>
												{{ position }}
											</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="col-span-4 lg:col-span-8">
								<div class="bg-white shadow rounded-2xl p-6">
									<div
										class="mb-4 border-b border-gray-200 dark:border-gray-700"
									>
										<ul
											class="flex flex-wrap -mb-px text-sm font-medium text-center"
											id="default-tab"
											data-tabs-toggle="#default-tab-content"
											role="tablist"
										>
											<li class="me-2" role="presentation">
												<button
													class="inline-block p-4 border-b-2 rounded-t-lg"
													id="incentives-tab"
													data-tabs-target="#incentives"
													type="button"
													role="tab"
													aria-controls="incentives"
													aria-selected="false"
												>
													Incentives
												</button>
											</li>
											<li class="me-2" role="presentation">
												<button
													class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
													id="transactions-tab"
													data-tabs-target="#transactions"
													type="button"
													role="tab"
													aria-controls="transactions"
													aria-selected="false"
												>
													Transactions
												</button>
											</li>
										</ul>
									</div>
									<div id="default-tab-content">
										<div
											class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800"
											id="incentives"
											role="tabpanel"
											aria-labelledby="incentives-tab"
										>
											<incentiveListHandler
												:employee_id="employeeData?.employee_id ?? ''"
											/>
										</div>
										<div
											class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800"
											id="transactions"
											role="tabpanel"
											aria-labelledby="transactions-tab"
										>
											<transactionsListHandler
												:employee_id="employeeData?.employee_id ?? ''"
											/>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
