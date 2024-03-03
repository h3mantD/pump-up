import React from "react";
import {
  Box,
  CircularProgress,
  Container,
  IconButton,
  MenuItem,
  Popover,
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
  const searchBoxRef = React.useRef(null);

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

  const handleClose = () => {
    setSearchResult([]);
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
              disabled={chatBot.isPending}
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
            <div ref={searchBoxRef} />
          </Stack>
        </form>
      </Box>
      <Popover
        open={searchResult.length > 0}
        anchorEl={searchBoxRef.current}
        onClose={handleClose}
        anchorOrigin={{
          vertical: "top",
          horizontal: "center"
        }}
        transformOrigin={{
          vertical: "top",
          horizontal: "center"
        }}
      >
        {searchResult.map((item) => (
          <MenuItem key={item.id} sx={{ width: 700 }}>
            {item.name}
          </MenuItem>
        ))}
      </Popover>
      <Container maxWidth="xl" sx={{ py: 5 }}>
        <Section title={"Today’s Deals"} products={products} />
        <ChatBot />
      </Container>
    </>
  );
}

export default Landing;
