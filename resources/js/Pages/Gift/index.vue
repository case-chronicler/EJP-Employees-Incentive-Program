<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

import Coffee from "@/Components/icons/gifts/Coffee.vue";
import Cupcake from "@/Components/icons/gifts/Cupcake.vue";
import Flower from "@/Components/icons/gifts/Flower.vue";
import Pizza from "@/Components/icons/gifts/Pizza.vue";
import QuillSliverPen from "@/Components/icons/gifts/QuillSliverPen.vue";

import Coin from "@/Components/icons/coin.vue";
import User from "@/Components/icons/user_2.vue";

const props = defineProps({
	incentive_gift: Array,
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

<style>
#recipent_container::-webkit-scrollbar {
	width: 8px;
}

#recipent_container::-webkit-scrollbar-track {
	background-color: #eee;
	border-radius: 100px;
}

#recipent_container::-webkit-scrollbar-thumb {
	background-color: #54636b8e;
	border-radius: 100px;
}
#recipent_container {
	scrollbar-width: thin !important;
}

#recipent_container {
	overflow-y: auto;
}
</style>

<template>
	<Head title="Gifts History" />

	<AuthenticatedLayout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				Gifts History
			</h2>
		</template>

		<div class="py-12">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
				<div class="container px-4 mx-auto">
					<div class="flex flex-wrap -m-4">
						<div
							class="w-full lg:w-1/3 p-4"
							v-for="(current_incentive_gift, key) in incentive_gift"
							:key="key"
						>
							<div class="bg-white shadow rounded-2xl overflow-hidden">
								<div class="pt-6 px-6 mb-10 flex justify-between items-center">
									<span
										class="inline-flex items-center justify-center p-2 w-[auto] h-12 bg-gray-100 rounded"
									>
										<Coffee
											h_class="h-8"
											w_class="w-[auto]"
											v-if="
												current_incentive_gift.incentives_icon_name === 'coffee'
											"
										/>
										<Cupcake
											h_class="h-8"
											w_class="w-[auto]"
											v-if="
												current_incentive_gift.incentives_icon_name ===
												'cupcake'
											"
										/>
										<Flower
											h_class="h-8"
											w_class="w-[auto]"
											v-if="
												current_incentive_gift.incentives_icon_name === 'flower'
											"
										/>
										<Pizza
											h_class="h-8"
											w_class="w-[auto]"
											v-if="
												current_incentive_gift.incentives_icon_name === 'pizza'
											"
										/>
										<QuillSliverPen
											h_class="h-8"
											w_class="w-[auto]"
											v-if="
												current_incentive_gift.incentives_icon_name ===
												'silver_pen'
											"
										/>
									</span>

									<Link
										:href="
											route(
												'incentive_gift.show',
												current_incentive_gift.incentives_gift_incentives_gift_type_id
											)
										"
										class="flex cursor-pointer items-center transition hover:text-slate-600"
									>
										<span
											class="py-1 px-2 bg-blue-50 text-xs text-indigo-500 rounded-full"
											data-ripple-dark="true"
										>
											Open
										</span>
									</Link>
								</div>
								<div class="px-6 mb-6">
									<p class="text-xs text-gray-500">
										{{ current_incentive_gift.incentives_gift_note }}
									</p>
								</div>
								<div class="p-6 bg-lightGray-50">
									<div class="flex -mx-2 mb-6">
										<div class="w-1/2 px-2">
											<p class="mb-2 text-xs font-medium">Date sent</p>
											<span class="inline-block text-xs text-gray-500">
												{{
													formatDate(
														current_incentive_gift.incentives_gift_created_at
													) ?? ""
												}}</span
											>
										</div>
										<div class="w-1/2 px-2">
											<p class="mb-2 text-xs font-medium">Gift total amount</p>

											<span
												class="inline-flex items-center mr-4 py-1 px-2 bg-green-50 rounded-full text-xs text-green-500"
											>
												<Coin h_class="h-[auto]" w_class="w-4" class="mr-1" />
												{{
													current_incentive_gift.incentives_gift_total_amount
												}}
											</span>
										</div>
									</div>

									<div class="w-full my-4">
										<p class="mb-2 text-xs font-medium">Recipient</p>

										<div
											class="max-w-full overflow-y-scroll pb-4 max-h-[50px]"
											id="recipent_container"
										>
											<span
												v-for="(
													giftedEmployee, index
												) in current_incentive_gift.gift_distribution"
												class="inline-flex items-center mr-3 mb-1 py-1 px-2 bg-gray-50 rounded-full text-xs text-gray-500"
											>
												{{ giftedEmployee.user_to_email }}
											</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="flex flex-wrap justify-between pt-6">
						<div class="w-full lg:w-auto mb-4 lg:mb-0 flex items-center"></div>
						<div class="w-full lg:w-auto flex items-center justify-center">
							<a
								class="inline-flex mr-3 items-center justify-center w-8 h-8 text-xs text-gray-500 border border-gray-300 bg-white hover:bg-indigo-50 rounded"
								href="#"
							>
								<svg
									width="6"
									height="8"
									viewbox="0 0 6 8"
									fill="none"
									xmlns="http://www.w3.org/2000/svg"
								>
									<path
										d="M2.53335 3.99999L4.86668 1.66666C5.13335 1.39999 5.13335 0.999992 4.86668 0.733325C4.60002 0.466659 4.20002 0.466659 3.93335 0.733325L1.13335 3.53333C0.866683 3.79999 0.866683 4.19999 1.13335 4.46666L3.93335 7.26666C4.06668 7.39999 4.20002 7.46666 4.40002 7.46666C4.60002 7.46666 4.73335 7.39999 4.86668 7.26666C5.13335 6.99999 5.13335 6.59999 4.86668 6.33333L2.53335 3.99999Z"
										fill="#A4AFBB"
									></path>
								</svg>
							</a>
							<a
								class="inline-flex mr-3 items-center justify-center w-8 h-8 text-xs text-gray-500 border border-gray-300 bg-white hover:bg-indigo-50 rounded"
								href="#"
								>1</a
							>
							<span class="inline-block mr-3">
								<svg
									class="h-3 w-3 text-gray-200"
									viewbox="0 0 12 4"
									fill="none"
									xmlns="http://www.w3.org/2000/svg"
								>
									<path
										d="M6 0.666687C5.26667 0.666687 4.66667 1.26669 4.66667 2.00002C4.66667 2.73335 5.26667 3.33335 6 3.33335C6.73333 3.33335 7.33333 2.73335 7.33333 2.00002C7.33333 1.26669 6.73333 0.666687 6 0.666687ZM1.33333 0.666687C0.6 0.666687 0 1.26669 0 2.00002C0 2.73335 0.6 3.33335 1.33333 3.33335C2.06667 3.33335 2.66667 2.73335 2.66667 2.00002C2.66667 1.26669 2.06667 0.666687 1.33333 0.666687ZM10.6667 0.666687C9.93333 0.666687 9.33333 1.26669 9.33333 2.00002C9.33333 2.73335 9.93333 3.33335 10.6667 3.33335C11.4 3.33335 12 2.73335 12 2.00002C12 1.26669 11.4 0.666687 10.6667 0.666687Z"
										fill="currentColor"
									></path>
								</svg>
							</span>
							<a
								class="inline-flex mr-3 items-center justify-center w-8 h-8 text-xs text-white bg-indigo-500 rounded"
								href="#"
								>12</a
							><a
								class="inline-flex mr-3 items-center justify-center w-8 h-8 text-xs text-gray-500 border border-gray-300 bg-white hover:bg-indigo-50 rounded"
								href="#"
								>13</a
							><a
								class="inline-flex mr-3 items-center justify-center w-8 h-8 text-xs text-gray-500 border border-gray-300 bg-white hover:bg-indigo-50 rounded"
								href="#"
								>14</a
							>
							<span class="inline-block mr-3">
								<svg
									class="h-3 w-3 text-gray-200"
									viewbox="0 0 12 4"
									fill="none"
									xmlns="http://www.w3.org/2000/svg"
								>
									<path
										d="M6 0.666687C5.26667 0.666687 4.66667 1.26669 4.66667 2.00002C4.66667 2.73335 5.26667 3.33335 6 3.33335C6.73333 3.33335 7.33333 2.73335 7.33333 2.00002C7.33333 1.26669 6.73333 0.666687 6 0.666687ZM1.33333 0.666687C0.6 0.666687 0 1.26669 0 2.00002C0 2.73335 0.6 3.33335 1.33333 3.33335C2.06667 3.33335 2.66667 2.73335 2.66667 2.00002C2.66667 1.26669 2.06667 0.666687 1.33333 0.666687ZM10.6667 0.666687C9.93333 0.666687 9.33333 1.26669 9.33333 2.00002C9.33333 2.73335 9.93333 3.33335 10.6667 3.33335C11.4 3.33335 12 2.73335 12 2.00002C12 1.26669 11.4 0.666687 10.6667 0.666687Z"
										fill="currentColor"
									></path>
								</svg>
							</span>
							<a
								class="inline-flex mr-3 items-center justify-center w-8 h-8 text-xs border border-gray-300 bg-white hover:bg-indigo-50 rounded"
								href="#"
								>62</a
							>
							<a
								class="inline-flex items-center justify-center w-8 h-8 text-xs text-gray-500 border border-gray-300 bg-white hover:bg-indigo-50 rounded"
								href="#"
							>
								<svg
									width="6"
									height="8"
									viewbox="0 0 6 8"
									fill="none"
									xmlns="http://www.w3.org/2000/svg"
								>
									<path
										d="M4.88663 3.52667L2.05996 0.700006C1.99799 0.637521 1.92425 0.587925 1.84301 0.554079C1.76177 0.520233 1.67464 0.502808 1.58663 0.502808C1.49862 0.502808 1.41148 0.520233 1.33024 0.554079C1.249 0.587925 1.17527 0.637521 1.1133 0.700006C0.989128 0.824915 0.919434 0.993883 0.919434 1.17001C0.919434 1.34613 0.989128 1.5151 1.1133 1.64001L3.4733 4.00001L1.1133 6.36001C0.989128 6.48491 0.919434 6.65388 0.919434 6.83001C0.919434 7.00613 0.989128 7.1751 1.1133 7.30001C1.17559 7.36179 1.24947 7.41068 1.33069 7.44385C1.41192 7.47703 1.49889 7.49385 1.58663 7.49334C1.67437 7.49385 1.76134 7.47703 1.84257 7.44385C1.92379 7.41068 1.99767 7.36179 2.05996 7.30001L4.88663 4.47334C4.94911 4.41136 4.99871 4.33763 5.03256 4.25639C5.0664 4.17515 5.08383 4.08801 5.08383 4.00001C5.08383 3.912 5.0664 3.82486 5.03256 3.74362C4.99871 3.66238 4.94911 3.58865 4.88663 3.52667Z"
										fill="#A4AFBB"
									></path>
								</svg>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
