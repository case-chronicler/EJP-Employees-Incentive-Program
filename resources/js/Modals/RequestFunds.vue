<script setup>
import { initFlowbite } from "flowbite";
import { onMounted, computed, ref } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import NoticeIcon from "@/Components/icons/notice_icon.vue";
import Coin from "@/Components/icons/coin.vue";

import { useModalStore } from "@/Store/modal";

const user = usePage().props.auth.user;
const userBalance = usePage().props.userBalance;

const modalStore = useModalStore();

const props = defineProps({
	allPositions: Array,
});

const form = useForm({
	amount: "",
	request_sent_by: user.user_id,
});

const isAmountAppropriate = computed(() => {
	if (
		!form.amount ||
		Number(form.amount) < 0 ||
		Number(form.amount) > userBalance
	) {
		return false;
	}

	return true;
});

const fundRequest_submit = () => {
	if (form.processing || !isAmountAppropriate) {
		return;
	}
	const fundsRequestForm = document.querySelector("#fundsRequestForm") ?? false;

	if (fundsRequestForm) {
		form.post(route("withdrawal_requests.store"), {
			onSuccess: () => form.reset(),
		});
	}
};

// initialize components based on data attribute selectors
onMounted(() => {
	initFlowbite();
});
</script>

<template>
	<!-- Main modal -->
	<div
		class="relative z-10"
		aria-labelledby="modal-title"
		role="dialog"
		aria-modal="true"
	>
		<div
			class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
		></div>

		<div class="fixed inset-0 z-10 w-screen overflow-y-auto">
			<div
				class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
			>
				<div
					class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
				>
					<div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
						<div class="sm:flex sm:items-start">
							<div
								class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-gray-100 sm:mx-0 sm:h-10 sm:w-10"
							>
								<Coin />
							</div>

							<div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
								<h3
									class="text-base font-semibold leading-6 text-gray-900"
									id="modal-title"
								>
									Request withdrawal
								</h3>
								<div class="mt-2">
									<p class="text-sm text-gray-500">
										Please review the details below before sending the
										invitation. Once invited, your new employee will be granted
										the selected roles. This action cannot be undone!
									</p>
								</div>
							</div>
						</div>
						<div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
							<form
								id="fundsRequestForm"
								class="space-y-6"
								action="#"
								method="POST"
							>
								<div class="mt-3 text-center sm:text-left">
									<span class="font-semibold leading-6 text-gray-400 text-sm">
										Balance (USD):
									</span>
									<span
										class="font-semibold leading-6 text-gray-800 text-sm ml-2"
									>
										{{ $page.props.userBalance }}
									</span>

									<div
										v-if="!isAmountAppropriate"
										class="font-semibold leading-6 text-red-800 text-sm mt-2"
									>
										Please enter a valid amount
									</div>
								</div>

								<div>
									<InputLabel
										class="block text-sm font-medium leading-6 text-gray-900"
										for="Amount"
										value="Amount"
									/>
									<div class="mt-2">
										<TextInput
											id="Amount"
											type="number"
											class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
											v-model="form.amount"
											required
										/>
									</div>
									<InputError class="mt-2" :message="form.errors.amount" />
								</div>

								<div></div>

								<div class="flex items-center gap-4">
									<Transition
										enter-from-class="opacity-0"
										leave-to-class="opacity-0"
										class="transition ease-in-out"
									>
										<p
											v-if="form.recentlySuccessful"
											class="text-sm text-gray-600"
										>
											Request sent
										</p>
									</Transition>
								</div>
							</form>
						</div>
					</div>
					<div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
						<button
							@click.prevent="fundRequest_submit"
							:disabled="!isAmountAppropriate || form.processing"
							type="button"
							class="inline-flex w-full justify-center rounded-md bg-gray-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 sm:ml-3 sm:w-auto"
							:class="{
								'hover:cursor-not-allowed': !isAmountAppropriate,
							}"
						>
							Send request
						</button>
						<button
							@click.prevent="modalStore.closeModal"
							type="button"
							class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
						>
							Cancel
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
