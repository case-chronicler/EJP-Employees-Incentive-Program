<script setup>
import { initFlowbite } from "flowbite";
import { onMounted } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import NoticeIcon from "@/Components/icons/notice_icon.vue";

import { useModalStore } from "@/Store/modal";

const user = usePage().props.auth.user;

const modalStore = useModalStore();

const props = defineProps({
	allPositions: Array,
});

const form = useForm({
	invite_email: "",
	invite_status: "pending",
	positions_assigned: [],
	invite_sent_by: user.user_id,
	days_before_withdrawal_eligibility: 10,
});

const newInviteForm_submit = () => {
	if (form.processing) {
		return;
	}
	const newInviteForm = document.querySelector("#newInviteForm") ?? false;

	if (newInviteForm) {
		form.post(route("invite.store"), {
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
							<NoticeIcon />

							<div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
								<h3
									class="text-base font-semibold leading-6 text-gray-900"
									id="modal-title"
								>
									Invite an employee to the platform
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
								id="newInviteForm"
								class="space-y-6"
								action="#"
								method="POST"
							>
								<div>
									<InputLabel
										class="block text-sm font-medium leading-6 text-gray-900"
										for="email_employee"
										value="Employee Email address"
									/>
									<div class="mt-2">
										<TextInput
											id="email_employee"
											type="text"
											class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
											v-model="form.invite_email"
											required
										/>
									</div>
									<InputError
										class="mt-2"
										:message="form.errors.invite_email"
									/>
								</div>

								<div>
									<InputLabel
										class="block text-sm font-medium leading-6 text-gray-900"
										for="days_before_withdrawal_eligibility"
										value="Date before first fund withdrawal can be made"
									/>
									<div class="mt-2">
										<select
											id="days_before_withdrawal_eligibility"
											class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
											required
											v-model="form.days_before_withdrawal_eligibility"
										>
											<option value="10">10 days</option>
											<option value="20">20 days</option>
											<option value="40">40 days</option>
											<option value="60">60 days</option>
											<option value="90">90 days</option>
										</select>
									</div>
									<InputError
										class="mt-2"
										:message="form.errors.days_before_withdrawal_eligibility"
									/>
								</div>

								<div>
									<fieldset>
										<label
											for="position_checkbox"
											class="block text-sm mb-2 font-medium leading-6 text-gray-900"
											>Role(s) to be assigned</label
										>
										<div class="grid grid-cols-3 gap-2">
											<div
												v-for="(position, index) in allPositions"
												:key="index"
												class="flex items-center mb-1"
											>
												<input
													:id="'checkbox' + index"
													type="checkbox"
													:value="position.position_id"
													v-model="form.positions_assigned"
													class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
												/>
												<InputLabel
													class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"
													:for="'checkbox' + index"
													:value="position.position_name"
												/>
											</div>
										</div>
										<InputError
											class="mt-2"
											:message="form.errors.positions_assigned"
										/>
									</fieldset>
								</div>

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
											Employee invited
										</p>
									</Transition>
								</div>
							</form>
						</div>
					</div>
					<div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
						<button
							@click.prevent="newInviteForm_submit"
							:disabled="form.processing"
							type="button"
							class="inline-flex w-full justify-center rounded-md bg-gray-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 sm:ml-3 sm:w-auto"
						>
							Send Invite
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
