
import React from "react";
import { Link } from "react-router-dom";

interface CategoryCardProps {
  title: string;
  subtitle: string;
  price: string;
  image: string;
  link: string;
}

const CategoryCard = ({ title, subtitle, price, image, link }: CategoryCardProps) => {
  return (
    <div className="relative overflow-hidden rounded-lg bg-cover bg-no-repeat bg-center h-80" style={{ backgroundImage: `url(${image})` }}>
      <div className="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
      <div className="absolute bottom-0 left-0 p-6 text-white">
        <p className="text-sm mb-1">{subtitle}</p>
        <h3 className="text-2xl font-bold mb-2">{title}</h3>
        <p className="mb-4">Starting with {price}</p>
        <Link to={link} className="inline-block bg-white text-gray-900 px-5 py-2 rounded-md hover:bg-gray-200 transition font-medium">
          SHOP NOW
        </Link>
      </div>
    </div>
  );
};

export default CategoryCard;
