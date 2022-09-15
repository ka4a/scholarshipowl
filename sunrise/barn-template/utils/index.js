/**
 * Get cookie from response.
 *
 * @param  {Object} res
 * @param  {String} key
 * @return {String|undefined}
 */
export function cookieFromResponse (res, key) {
  if (!res.headers.cookie) {
    return
  }

  const cookie = res.headers.cookie.split(';').find(
    c => c.trim().startsWith(`${key}=`)
  )

  if (cookie) {
    return cookie.split('=')[1]
  }
}
