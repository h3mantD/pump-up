import React from "react";
import {
  Box,
  CircularProgress,
  Container,
  IconButton,
  Stack,
  TextField,
  Typography
} from "@mui/material";
import SearchIcon from "@mui/icons-material/Search";
import ChatBot from "@/containers/ChatBot/ChatBot";
import Section from "@/components/Section/Section";
import { useProducts } from "@/services/products.service.js";
import { useBotSearch } from "@/services/bot.service";
import { useForm } from "react-hook-form";
import { yupResolver } from "@hookform/resolvers/yup";
import * as yup from "yup";

function Landing() {
  const products = useProducts();

  const inputSchema = yup.object({
    search: yup.string().required("Search is required")
  });
  const [searchResult, setSearchResult] = React.useState([]);

  const chatBot = useBotSearch(handleBotResponse);

  const { register, handleSubmit } = useForm({
    resolver: yupResolver(inputSchema)
  });

  function handleBotResponse(data) {
    setSearchResult(JSON.parse(data.data.content));
  }

  const onSubmit = ({ search }) => {
    chatBot.mutate({ role: "user", content: search });
  };

  return (
    <>
      <Box className="background" sx={{ px: 10 }}>
        <form onSubmit={handleSubmit(onSubmit)}>
          <Stack alignItems="center" spacing={2} sx={{ py: 15 }}>
            <Typography variant="h2" color="whitesmoke" textAlign="center">
              “The only person you are destined to become is the person you
              decide to be.”
            </Typography>
            <Typography
              variant="h4"
              color="whitesmoke"
              textAlign="center"
              sx={{ py: 2 }}
              fontStyle="italic"
            >
              - Ralph Waldo Emerson
            </Typography>
            <TextField
              {...register("search")}
              type="search"
              variant="outlined"
              sx={{
                background: "white",
                borderRadius: 1,
                ml: 2,
                maxWidth: "60%"
              }}
              onChange={(e) => {
                if (e.target.value === "") {
                  setSearchResult([]);
                }
              }}
              fullWidth
              placeholder="Search by AI..."
              InputProps={{
                endAdornment: chatBot.isPending ? (
                  <CircularProgress />
                ) : (
                  <IconButton type="submit">
                    <SearchIcon />
                  </IconButton>
                )
              }}
            />
          </Stack>
        </form>
      </Box>
      <Container maxWidth="xl" sx={{ py: 5 }}>
        <Section title={"Today’s Deals"} products={products} />
        <ChatBot />
      </Container>
    </>
  );
}

export default Landing;
