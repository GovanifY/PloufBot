rm frame.jpg
rm video.flv
youtube-dl -f flv -o video.flv $1
duration=`ffmpeg -i video.flv 2>&1 | grep "Duration"| cut -d ' ' -f 4 | sed s/,// | sed 's@\..*@@g' | awk '{ split($1, A, ":"); split(A[3], B, "."); print 3600*A[1] + 60*A[2] + B[1] }'`
random=`shuf -i 0-$duration -n 1`
ffmpeg  -ss $random -i video.flv -vframes 1 frame.jpg
