import api from "@/helpers/api";
import endpoints from "@/helpers/endpoints";
import { useMutation } from "@tanstack/react-query";

export const useChatBot = (handleChatBotResponse) => {
  return useMutation({
    mutationKey: ["chatbot"],
    mutationFn: (chat) => {
      return api({
        method: "post",
        url: endpoints.groq,
        data: { messages: [{ role: "user", content: chat }] }
      });
    },
    onSettled: handleChatBotResponse
  });
};
