import {
  Box,
  Container,
  InputAdornment,
  Stack,
  TextField,
  Typography
} from "@mui/material";
import SearchIcon from "@mui/icons-material/Search";
import ChatBot from "@/containers/ChatBot/ChatBot";
import Section from "@/components/Section/Section";
// import d from "@/data/d.json";
import { useProducts } from "@/services/products.service.js";

function Landing() {
  const products = useProducts();

  return (
    <>
      <Box className="background" sx={{ px: 10 }}>
        <Stack alignItems="center" spacing={2} sx={{ py: 15 }}>
          <Typography variant="h2" color="whitesmoke" textAlign="center">
            “The only person you are destined to become is the person you decide
            to be.”
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
            variant="outlined"
            sx={{
              background: "white",
              borderRadius: 1,
              ml: 2,
              maxWidth: "60%"
            }}
            fullWidth
            placeholder="Search..."
            InputProps={{
              startAdornment: (
                <InputAdornment position="start">
                  <SearchIcon />
                </InputAdornment>
              )
            }}
          />
        </Stack>
      </Box>
      <Container maxWidth="xl" sx={{ py: 5 }}>
        <Section title={"Today’s Deals"} products={products} />
        <ChatBot />
      </Container>
    </>
  );
}

export default Landing;
