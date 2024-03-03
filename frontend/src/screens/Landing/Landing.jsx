import {
  Container,
  Grid,
  InputAdornment,
  Stack,
  TextField,
  Typography
} from "@mui/material";
import SearchIcon from "@mui/icons-material/Search";
import d from "@/data/d.json";
import Product from "@/components/Product/Product.jsx";
import ChatBot from "@/containers/ChatBot/ChatBot";

function Landing() {
  return (
    <Container maxWidth="xl" className="background">
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
          sx={{ background: "white", borderRadius: 1, ml: 2, maxWidth: "60%" }}
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

      <Grid container spacing={10}>
        {d.map((item) => (
          <Grid key={d.id} item xs={12} sm={6} md={4}>
            <Product item={item} />
          </Grid>
        ))}
      </Grid>
      <ChatBot />
    </Container>
  );
}

export default Landing;
