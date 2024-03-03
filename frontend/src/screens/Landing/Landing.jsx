import {
  Box,
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
import { Swiper, SwiperSlide } from "swiper/react";
// Import Swiper styles
import "swiper/css";
import "swiper/css/pagination";

// import required modules
import { Autoplay } from "swiper/modules";

function Landing() {
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
      <Container maxWidth="xl" sx={{ pt: 5 }}>
        <Typography variant="h4" color="black" sx={{ pb: 1 }}>
          Today&rsquo;s Deals
        </Typography>
        <Swiper
          slidesPerView={3}
          spaceBetween={30}
          loop={true}
          autoplay={{
            delay: 2500,
            disableOnInteraction: false
          }}
          modules={[Autoplay]}
        >
          {d.map((item) => (
            <SwiperSlide key={item.id}>
              <Product item={item} />
            </SwiperSlide>
          ))}
        </Swiper>
        <ChatBot />
      </Container>
    </>
  );
}

export default Landing;
