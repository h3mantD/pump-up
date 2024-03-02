import {
  Container,
  InputAdornment,
  Stack,
  TextField,
  Typography
} from "@mui/material";
import SearchIcon from "@mui/icons-material/Search";

function Landing() {
  return (
    <Container maxWidth="xl">
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
    </Container>
  );
}

export default Landing;
