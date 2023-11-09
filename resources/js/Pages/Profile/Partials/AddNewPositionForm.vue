<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
	mustVerifyEmail: Boolean,
	status: String,
	allPositions: Array,
	isEmployeeAnAttorney: Boolean,
});

const user = usePage().props.auth.user;

const form = useForm({
	position_name: "",
});

const showActivePosition = ref(false);
</script>

<template>
	<section>
		<header>
			<h2 class="text-lg font-medium text-gray-900">Positions</h2>

			<p class="mt-1 text-sm text-gray-600">Manage Positions in the Firm.</p>
		</header>

		<div class="flex flex-wrap justify-start overflow-hidden">
			<label class="grow py-3 font-medium" for="collapse"
				>View active positions</label
			>
			<input
				@change="showActivePosition = !showActivePosition"
				class="peer mx-4 my-3 h-0 w-0 appearance-none rounded border text-slate-800 accent-slate-600 opacity-0"
				type="checkbox"
				name="collapse"
				id="collapse"
			/>
			<svg
				xmlns="http://www.w3.org/2000/svg"
				fill="none"
				viewBox="0 0 24 24"
				stroke-width="1.5"
				stroke="currentColor"
				class="mx-4 my-3 h-6 w-6 transition-all duration-200 peer-checked:rotate-45"
			>
				<path
					stroke-linecap="round"
					stroke-linejoin="round"
					d="M12 6v12m6-6H6"
				/>
			</svg>
		</div>
		<Transition>
			<div
				v-if="showActivePosition"
				class="grid md:grid-cols-3 grid-cols-2 gap-3 max-h-[150px] overflow-y-scroll"
			>
				<div
					v-if="allPositions.length == 0"
					class="text-sm font-normal text-gray-700 my-3"
				>
					No position in the firm
				</div>
				<div
					v-for="current_position in allPositions"
					:key="current_position.position_id"
					class="inline-block max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow mr-2"
				>
					<p class="text-sm font-normal text-gray-700 dark:text-gray-400">
						{{ current_position.position_name }}
					</p>
				</div>
			</div>
		</Transition>

		<form
			@submit.prevent="
				form.post(route('position.store'), { onSuccess: () => form.reset() })
			"
			class="mt-6 space-y-6"
		>
			<div>
				<InputLabel for="position_name" value="Position" />

				<TextInput
					id="position_name"
					type="text"
					class="mt-1 block w-full"
					v-model="form.position_name"
					required
					autofocus
					autocomplete="position_name"
				/>

				<template
					v-if="
						form.errors.position_name ===
						'The position name has already been taken.'
					"
				>
					<InputError
						class="mt-2"
						:message="'This Position already exists in the firm'"
					/>
				</template>
				<template v-else>
					<InputError class="mt-2" :message="form.errors.position_name" />
				</template>
			</div>

			<div
				v-show="props.status === 'verification-link-sent'"
				class="mt-2 font-medium text-sm text-green-600"
			>
				A new verification link has been sent to your email address.
			</div>

			<div class="flex items-center gap-4">
				<PrimaryButton :disabled="form.processing">Add position</PrimaryButton>

				<Transition
					enter-from-class="opacity-0"
					leave-to-class="opacity-0"
					class="transition ease-in-out"
				>
					<p v-if="form.recentlySuccessful" class="text-sm text-gray-600">
						Position Added.
					</p>
				</Transition>
			</div>
		</form>
	</section>
</template>

<style scoped>
.v-enter-active,
.v-leave-active {
	transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
	opacity: 0;
}
</style>
