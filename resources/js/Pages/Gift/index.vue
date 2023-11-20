<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

import Coffee from "@/Components/icons/gifts/Coffee.vue";
import Cupcake from "@/Components/icons/gifts/Cupcake.vue";
import Flower from "@/Components/icons/gifts/Flower.vue";
import Pizza from "@/Components/icons/gifts/Pizza.vue";
import QuillSliverPen from "@/Components/icons/gifts/QuillSliverPen.vue";

import Coin from "@/Components/icons/coin.vue";
import User from "@/Components/icons/user_2.vue";
import { computed } from "vue";

const props = defineProps({
	incentive_gift: Object,
});

const getincentive_gift = computed(() => {
	return props.incentive_gift.data ?? [];
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
#recipent_container::-webkit-scrollbar,
#note_container::-webkit-scrollbar {
	width: 8px;
}

#note_container::-webkit-scrollbar-track,
#recipent_container::-webkit-scrollbar-track {
	background-color: #eee;
	border-radius: 100px;
}

#note_container::-webkit-scrollbar-thumb,
#recipent_container::-webkit-scrollbar-thumb {
	background-color: #54636b8e;
	border-radius: 100px;
}
#note_container,
#recipent_container {
	scrollbar-width: thin !important;
}

#note_container,
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
							v-for="(current_incentive_gift, key) in getincentive_gift"
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
								<div class="px-6 mb-2">
									<p
										class="text-xs text-gray-500 max-h-[80px] h-[80px]"
										id="note_container"
									>
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
								class="inline-flex mr-3 items-center justify-center px-3 py-2 text-sm text-gray-500 border border-gray-300 bg-white hover:bg-indigo-50 rounded-2xl"
								v-if="incentive_gift.links.prev"
								:href="incentive_gift.links.prev"
							>
								Previous
							</a>

							<a
								class="inline-flex items-center justify-center px-3 py-2 text-sm text-gray-500 border border-gray-300 bg-white hover:bg-indigo-50 rounded-2xl"
								v-if="incentive_gift.links.next"
								:href="incentive_gift.links.next"
							>
								Next
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
