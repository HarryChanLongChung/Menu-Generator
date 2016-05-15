# Menu-Generator
Using .php to accept uploaded image and text file to generate a menu.

# 2016/04/16
Function acquired:
  1. Accept .jpg as upload image
  2. Accept .txt as upload content
  3. Using default font-type
  4. Generate the final menu using both files from upload/ and default files

Function to be acquired:
  1. Allow user to choose font-type
  2. Calculate the location of content base on the image file size

# 2016/04/25
Function acquired:
  1. Allow user to use the selection to choose desired font-type
  2. Apply text to the image according to certain algorthim

# 2016/05/13
Interface changing:
  1. Fix unnatural alignment in the upload form

# Demo Video:
  [Youtube Video](https://www.youtube.com/watch?v=R_oTrRscYZg)

# Function explaination:
  1. this website will use your uploaded iamge as the background image for the menu
  2. there is five default font-types included under the 'font' file 
  3. the .txt file need to under certain format:
  
  ```c
  t:40
  d:30
  p:35
  =end of define=
  (t)Dish Name 1
  (d)detail of dish 1
  (p)57
  ```
  
  4. the fisrt part is used to define all font size
  5. the '=end of define=' line is used as a format to indicate start of the content
  6. any text after the "(t)" or "(d)" or "(p)" will be shown on the image as the size declared above
