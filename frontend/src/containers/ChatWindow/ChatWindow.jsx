import React from "react";
import SmartToyIcon from "@mui/icons-material/SmartToy";
import {
  Card,
  CardContent,
  CardHeader,
  Divider,
  TextField,
  Paper,
  Typography,
  Stack,
  IconButton
} from "@mui/material";
import ArrowForwardIcon from "@mui/icons-material/ArrowForward";
import * as yup from "yup";
import { useForm } from "react-hook-form";
import { yupResolver } from "@hookform/resolvers/yup";
import { useChatBot } from "@/services/bot.service";

function ChatWindow({ history, setHistory }) {
  const inputSchema = yup.object({
    chat: yup.string().required("Chat is required")
  });
  const chatBot = useChatBot(handleChatBotResponse);

  const { register, handleSubmit, reset } = useForm({
    resolver: yupResolver(inputSchema)
  });

  function handleChatBotResponse(data) {
    setHistory([...history, data.data]);
  }

  const onSubmit = ({ chat }) => {
    setHistory([
      ...history,
      { role: "user", content: chat, timeStamp: Date.now() }
    ]);
    chatBot.mutate({ role: "user", content: chat });
    reset();
  };

  return (
    <Card sx={{ width: 300 }}>
      <CardHeader avatar={<SmartToyIcon />} title="ChatBot" />
      <Divider />
      <CardContent sx={{ height: 400, overflowY: "scroll" }}>
        <Stack spacing={2}>
          {history.map((item) => {
            if (item.role === "user") {
              return (
                <Paper
                  key={item.content}
                  sx={{ p: 1, background: "lightblue", ml: 3 }}
                >
                  <Typography
                    variant="body1"
                    color="text.secondary"
                    textAlign="right"
                  >
                    {item.content}
                  </Typography>
                </Paper>
              );
            } else {
              return (
                <Paper
                  key={item.timeStamp}
                  sx={{ p: 1, background: "whitesmoke", mr: 3 }}
                >
                  <Typography variant="body1" color="text.secondary">
                    {item.content}
                  </Typography>
                </Paper>
              );
            }
          })}
        </Stack>
      </CardContent>
      <CardContent>
        <form onSubmit={handleSubmit(onSubmit)}>
          {chatBot.isLoading ? (
            <Typography>Loading...</Typography>
          ) : (
            <TextField
              {...register("chat")}
              size="small"
              fullWidth
              placeholder="Type your message here..."
              autoComplete="off"
              variant="outlined"
              InputProps={{
                endAdornment: (
                  <IconButton type="submit">
                    <ArrowForwardIcon />
                  </IconButton>
                )
              }}
            />
          )}
        </form>
      </CardContent>
    </Card>
  );
}

export default ChatWindow;
