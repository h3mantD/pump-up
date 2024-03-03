import { Skeleton, Typography } from "@mui/material";
import Product from "@/components/Product/Product.jsx";
import { Swiper, SwiperSlide } from "swiper/react";
// Import Swiper styles
import "swiper/css";
import "swiper/css/pagination";

// import required modules
import { Autoplay } from "swiper/modules";

function Section({ title, products }) {
  if (products.isLoading || products.isError) {
    return (
      <>
        <Typography variant="h4" color="black" sx={{ pb: 1 }}>
          {title}
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
          {Array(10).map((_, id) => (
            <SwiperSlide key={id}>
              <Skeleton variant="rectangular" width={250} height={250} />
            </SwiperSlide>
          ))}
        </Swiper>
      </>
    );
  }

  return (
    <>
      <Typography variant="h4" color="black" sx={{ pb: 1 }}>
        {title}
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
        {products.data.map((product) => (
          <SwiperSlide key={product.id}>
            <Product item={product} />
          </SwiperSlide>
        ))}
      </Swiper>
    </>
  );
}

export default Section;
