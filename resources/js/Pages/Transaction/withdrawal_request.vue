<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import WithdrawalRequestAction from "@/Modals/WithdrawalRequestAction.vue";

import Coin from "@/Components/icons/coin.vue";

import { Head, Link, useForm, usePage, router } from "@inertiajs/vue3";
import { computed, reactive, ref } from "vue";

import { useModalStore } from "@/Store/modal";

const modalStore = useModalStore();

const props = defineProps({
	withdrawal_request_data: Object,
});

const isEmployeeAnAttorney = usePage().props.isEmployeeAnAttorney;

const action = ref("");

const initActionModal = (selectedAction) => {
	if (selectedAction == "") {
		return;
	}

	action.value = selectedAction;

	modalStore.openWithdrawalRequestActionModal();
};

const goToWithdrawals = () => {
	router.visit("/withdrawals", { preserveScroll: true });
};
</script>

<template>
	<WithdrawalRequestAction
		:action="action"
		:amount="withdrawal_request_data.withdrawal_request_amount"
		:sender="withdrawal_request_data.user_email"
		:withdrawal_request_link_id="
			withdrawal_request_data.withdrawal_request_link_id
		"
		v-if="modalStore.currentModal === 'WITHDRAWAL_REQUEST_ACTION'"
	/>

	<Head title="Withdrawal Request" />

	<AuthenticatedLayout>
		<div class="my-16">
			<div class="max-w-5xl mx-auto px-6 lg:px-8 space-y-14">
				<div class="mb-4">
					<PrimaryButton
						@click="goToWithdrawals"
						type="button"
						class="mx-2 float-right"
						>withdrawals</PrimaryButton
					>
					<div class="clear-both"></div>
				</div>

				<div>
					<div class="px-4 sm:px-0">
						<h3 class="text-base font-semibold leading-7 text-gray-900">
							Withdrawal Information
						</h3>
						<p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">
							Personal details and application.
						</p>
					</div>
					<div class="mt-6 border-t border-gray-300">
						<dl class="divide-y divide-gray-300">
							<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
								<dt class="text-sm font-medium leading-6 text-gray-900">
									Full name
								</dt>
								<dd
									class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"
								>
									{{ withdrawal_request_data.user_fullname }}
								</dd>
							</div>
							<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
								<dt class="text-sm font-medium leading-6 text-gray-900">
									Email address
								</dt>
								<dd
									class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"
								>
									{{ withdrawal_request_data.user_email }}
								</dd>
							</div>
							<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
								<dt class="text-sm font-medium leading-6 text-gray-900">
									Amount withdrawn
								</dt>
								<dd
									class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"
								>
									<div class="inline-block">
										<Coin class="inline-block" />
										{{ withdrawal_request_data.withdrawal_request_amount }}
									</div>
								</dd>
							</div>
							<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
								<dt class="text-sm font-medium leading-6 text-gray-900">
									Status
								</dt>
								<dd
									class="mt-1 font-semibold text-sm leading-6 sm:col-span-2 sm:mt-0 uppercase"
									:class="{
										'text-amber-500':
											withdrawal_request_data.withdrawal_request_status ==
											'pending',
										'text-red-700':
											withdrawal_request_data.withdrawal_request_status ==
												'failed' ||
											withdrawal_request_data.withdrawal_request_status ==
												'rejected',
										'text-green-700':
											withdrawal_request_data.withdrawal_request_status ==
											'success',
									}"
								>
									{{ withdrawal_request_data.withdrawal_request_status }}

									<span
										v-if="withdrawal_request_data?.withdrawal_remark ?? false"
										class="text-xs"
									>
										({{ withdrawal_request_data?.withdrawal_remark }})
									</span>
								</dd>
							</div>
							<div
								v-if="
									withdrawal_request_data.withdrawal_request_status ===
										'pending' && isEmployeeAnAttorney
								"
								class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0"
							>
								<div></div>
								<div></div>
								<div class="py-3 sm:flex sm:flex-row-reverse">
									<button
										@click.prevent="initActionModal('approve')"
										type="button"
										class="text-green-900 uppercase inline-flex w-full justify-center rounded-md px-3 py-2 text-sm font-semibold shadow-sm sm:ml-3 sm:w-auto bg-white ring-1 ring-inset ring-green-300 hover:bg-green-50 sm:mt-0 sm:w-auto"
									>
										approve
									</button>
									<button
										@click.prevent="initActionModal('reject')"
										type="button"
										class="mt-3 uppercase inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-red-900 shadow-sm ring-1 ring-inset ring-red-300 hover:bg-red-50 sm:mt-0 sm:w-auto"
									>
										reject
									</button>
								</div>
							</div>
						</dl>
					</div>
				</div>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
