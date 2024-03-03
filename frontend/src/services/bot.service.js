import api from "@/helpers/api";
import endpoints from "@/helpers/endpoints";
import { useMutation } from "@tanstack/react-query";

export const useChatBot = (handleChatBotResponse) => {
  return useMutation({
    mutationKey: ["chatbot"],
    mutationFn: (message) => {
      return api({
        method: "post",
        url: endpoints.groq,
        param: { chat_role: "chatbot" },
        data: { messages: [message] }
      });
    },
    onSettled: handleChatBotResponse
  });
};
