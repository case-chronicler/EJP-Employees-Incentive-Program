import { defineStore } from "pinia";
import { ref } from "vue";

export const useModalStore = defineStore("modal", () => {
	const currentModal = ref("");

	const openNewEmployeeInviteModal = () => {
		currentModal.value = "NEW_EMPLOYEE_INVITE";
	};

	const openSendGiftModal = () => {
		currentModal.value = "SEND_GIFT";
	};

	const openSendGift_GroupModal = () => {
		currentModal.value = "SendGift_Group";
	};

	const openRequestFundsModal = () => {
		currentModal.value = "REQUEST_FUNDS";
	};

	const closeModal = () => {
		currentModal.value = "";
	};

	return {
		currentModal,
		openNewEmployeeInviteModal,
		openSendGiftModal,
		openSendGift_GroupModal,
		openRequestFundsModal,
		closeModal,
	};
});
