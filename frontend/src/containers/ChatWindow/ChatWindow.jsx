import React from "react";
import SmartToyIcon from "@mui/icons-material/SmartToy";
import {
  Card,
  CardContent,
  CardHeader,
  Divider,
  TextField,
  InputAdornment
} from "@mui/material";
import ArrowForwardIcon from "@mui/icons-material/ArrowForward";
import * as yup from "yup";
import { useForm } from "react-hook-form";
import { yupResolver } from "@hookform/resolvers/yup";
import { useChatBot } from "@/services/bot.service";

function ChatWindow() {
  const [history, setHistory] = React.useState([]);
  const inputSchema = yup.object({
    chat: yup.string().required("Chat is required")
  });
  const chatBot = useChatBot(handleChatBotResponse);

  const { register, handleSubmit } = useForm({
    resolver: yupResolver(inputSchema)
  });

  function handleChatBotResponse(data) {
    console.log(data);
  }

  const onSubmit = ({ chat }) => {
    setHistory([...history, { user: chat }]);
    chatBot.mutate(chat);
  };

  return (
    <Card sx={{ width: 300 }}>
      <CardHeader avatar={<SmartToyIcon />} title="ChatBot" />
      <Divider />
      <CardContent sx={{ height: 200 }}></CardContent>
      <CardContent>
        <form onSubmit={handleSubmit(onSubmit)}>
          <TextField
            {...register("chat")}
            size="small"
            fullWidth
            placeholder="Type your message here..."
            variant="outlined"
            InputProps={{
              endAdornment: (
                <InputAdornment position="end">
                  <ArrowForwardIcon />
                </InputAdornment>
              )
            }}
          />
        </form>
      </CardContent>
    </Card>
  );
}

export default ChatWindow;
